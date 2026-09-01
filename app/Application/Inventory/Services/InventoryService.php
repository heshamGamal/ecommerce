<?php

namespace App\Application\Inventory\Services;

use App\Domain\Models\InventoryMovement;
use App\Domain\Models\InventoryReservation;
use App\Domain\Models\WarehouseInventory;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RuntimeException;

class InventoryService
{
    public function __construct(
        private readonly DatabaseManager $database,
    ) {
    }

    public function receive(
        string $warehouseId,
        string $productId,
        ?string $variantId,
        int $quantity,
        ?string $referenceType = null,
        ?string $referenceId = null,
        ?string $notes = null,
    ): WarehouseInventory {
        if ($quantity <= 0) {
            throw new RuntimeException(
                'Received quantity must be greater than zero.'
            );
        }

        return $this->database->transaction(function () use (
            $warehouseId,
            $productId,
            $variantId,
            $quantity,
            $referenceType,
            $referenceId,
            $notes,
        ) {
            $inventory = $this->getOrCreateInventory(
                $warehouseId,
                $productId,
                $variantId,
            );

            $inventory->increment('quantity', $quantity);

            $this->recordMovement(
                inventory: $inventory,
                type: 'receive',
                quantity: $quantity,
                referenceType: $referenceType,
                referenceId: $referenceId,
                notes: $notes,
            );

            return $inventory->fresh();
        });
    }

    public function reserve(
        string $warehouseId,
        string $productId,
        ?string $variantId,
        int $quantity,
        ?string $orderId = null,
        ?\DateTimeInterface $expiresAt = null,
    ): InventoryReservation {
        if ($quantity <= 0) {
            throw new RuntimeException(
                'Reservation quantity must be greater than zero.'
            );
        }

        return $this->database->transaction(function () use (
            $warehouseId,
            $productId,
            $variantId,
            $quantity,
            $orderId,
            $expiresAt,
        ) {
            $inventory = $this->lockInventory(
                $warehouseId,
                $productId,
                $variantId,
            );

            $available = $inventory->quantity
                - $inventory->reserved_quantity;

            if ($available < $quantity) {
                throw new RuntimeException(
                    'Insufficient inventory.'
                );
            }

            $inventory->increment(
                'reserved_quantity',
                $quantity
            );

            return InventoryReservation::create([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'order_id' => $orderId,
                'quantity' => $quantity,
                'status' => 'active',
                'expires_at' => $expiresAt,
            ]);
        });
    }

    private function getOrCreateInventory(
        string $warehouseId,
        string $productId,
        ?string $variantId,
    ): WarehouseInventory {
        return WarehouseInventory::query()->firstOrCreate(
            [
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
            ],
            [
                'quantity' => 0,
                'reserved_quantity' => 0,
            ]
        );
    }

    private function lockInventory(
        string $warehouseId,
        string $productId,
        ?string $variantId,
    ): WarehouseInventory {
        $inventory = WarehouseInventory::query()
            ->where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->when(
                $variantId,
                fn ($query) => $query->where(
                    'product_variant_id',
                    $variantId
                ),
                fn ($query) => $query->whereNull(
                    'product_variant_id'
                )
            )
            ->lockForUpdate()
            ->first();

        if (!$inventory) {
            throw (new ModelNotFoundException)
                ->setModel(
                    WarehouseInventory::class
                );
        }

        return $inventory;
    }

    private function recordMovement(
        WarehouseInventory $inventory,
        string $type,
        int $quantity,
        ?string $referenceType,
        ?string $referenceId,
        ?string $notes,
    ): void {
        InventoryMovement::create([
            'warehouse_id' => $inventory->warehouse_id,
            'product_id' => $inventory->product_id,
            'product_variant_id' => $inventory->product_variant_id,
            'type' => $type,
            'quantity' => $quantity,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
        ]);
    }
}

<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Domain\Contracts\Repositories\ProductRepositoryInterface;
use App\Domain\Models\Product;
use App\Domain\Models\ProductVariant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $perPage = max(1, min($perPage, 100));
        return Product::with(['category', 'variants', 'media'])
            ->when(!empty($filters['category_id']), fn ($q) => $q->where('category_id', $filters['category_id']))
            ->when(array_key_exists('is_active', $filters), fn ($q) => $q->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN)))
            ->when(!empty($filters['search']), fn ($q) => $q->where(fn ($q) => $q->where('name', 'like', '%'.$filters['search'].'%')->orWhere('sku', 'like', '%'.$filters['search'].'%')))
            ->latest()->paginate($perPage);
    }

    public function findById(string $id): ?Product { return Product::with(['category', 'variants', 'media'])->find($id); }
    public function create(array $data): Product { return Product::create($data); }
    public function update(string $id, array $data): bool { return ($product = Product::find($id)) ? $product->update($data) : false; }
    public function delete(string $id): bool { return ($product = Product::find($id)) ? (bool) $product->delete() : false; }

    public function syncVariants(string $productId, array $variants): void
    {
        DB::transaction(function () use ($productId, $variants): void {
            ProductVariant::where('product_id', $productId)->delete();
            foreach ($variants as $variant) {
                ProductVariant::create([
                    'product_id' => $productId,
                    'attributes' => $variant['attributes'] ?? [],
                    'price' => $variant['price'] ?? null,
                    'compare_price' => $variant['compare_price'] ?? null,
                    'stock_quantity' => (int) ($variant['stock'] ?? $variant['stock_quantity'] ?? 0),
                    'sku' => (string) ($variant['sku'] ?? ''),
                    'image' => $variant['image'] ?? null,
                    'is_active' => $variant['is_active'] ?? true,
                ]);
            }
        });
    }

    public function adjustStock(string $productId, int $quantityChange): bool
    {
        return DB::transaction(function () use ($productId, $quantityChange): bool {
            $product = Product::whereKey($productId)->lockForUpdate()->first();
            if (!$product) return false;
            if ($product->stock + $quantityChange < 0) throw new InvalidArgumentException('لا يمكن خفض المخزون إلى ما دون الصفر.');
            $product->stock += $quantityChange;
            return $product->save();
        });
    }

    public function bulkUpdateStatus(array $productIds, bool $isActive): int { return Product::whereIn('id', $productIds)->update(['is_active' => $isActive]); }
    public function bulkDelete(array $productIds): int { return Product::whereIn('id', $productIds)->delete(); }
}

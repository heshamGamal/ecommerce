<?php
namespace App\Application\Products\Services;

use App\Application\Products\DTOs\ProductData;
use App\Domain\Contracts\Repositories\ProductRepositoryInterface;
use App\Domain\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function getCatalog(array $filters = [], int $perPage = 15)
    {
        return $this->productRepository->getAllPaginated($filters, $perPage);
    }

    public function getDetails(string $id): Product
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            throw new ModelNotFoundException("المنتج المطلوب غير موجود.");
        }
        return $product;
    }

    public function createProduct(ProductData $dto): Product
    {
        return DB::transaction(function () use ($dto) {
            $product = $this->productRepository->create([
                'name'         => $dto->name,
                'slug'         => $dto->slug,
                'description'  => $dto->description,
                'price'        => $dto->price,
                'sale_price'   => $dto->salePrice,
                'sale_ends_at' => $dto->saleEndsAt,
                'sku'          => $dto->sku,
                'stock'        => $dto->stock,
                'category_id'  => $dto->categoryId,
                'is_active'    => $dto->isActive,
            ]);

            if (!empty($dto->variants)) {
                $this->productRepository->syncVariants($product->id, $dto->variants);
            }

            return $product;
        });
    }

    public function updateProduct(string $id, ProductData $dto): bool
    {
        return DB::transaction(function () use ($id, $dto) {
            $existingProduct = $this->productRepository->findById($id);
            if (!$existingProduct) {
                throw new ModelNotFoundException("المنتج المراد تعديله غير موجود.");
            }

            $updated = $this->productRepository->update($id, array_filter([
                'name'         => $dto->name,
                'slug'         => $dto->slug,
                'description'  => $dto->description,
                'price'        => $dto->price,
                'sale_price'   => $dto->salePrice,
                'sale_ends_at' => $dto->saleEndsAt,
                'sku'          => $dto->sku,
                'stock'        => $dto->stock,
                'category_id'  => $dto->categoryId,
                'is_active'    => $dto->isActive,
            ], fn($val) => $val !== null));

            if (!empty($dto->variants)) {
                $this->productRepository->syncVariants($id, $dto->variants);
            }

            return $updated;
        });
    }

    public function deleteProduct(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            \App\Domain\Models\ProductVariant::where('product_id', $id)->delete();
            return $this->productRepository->delete($id);
        });
    }

    public function manualStockAdjustment(string $productId, int $quantityChange): void
    {
        $adjusted = $this->productRepository->adjustStock($productId, $quantityChange);
        if (!$adjusted) {
            throw new Exception("فشل تعديل المخزون.");
        }
    }

    public function applyFlashSale(string $productId, float $salePrice, string $endDate): void
    {
        $this->productRepository->update($productId, [
            'sale_price'   => $salePrice,
            'sale_ends_at' => $endDate,
        ]);
    }

    public function bulkChangeStatus(array $ids, bool $status): int
    {
        return DB::transaction(function () use ($ids, $status) {
            return $this->productRepository->bulkUpdateStatus($ids, $status);
        });
    }

    public function bulkRemove(array $ids): int
    {
        return DB::transaction(function () use ($ids) {
            \App\Domain\Models\ProductVariant::whereIn('product_id', $ids)->delete();
            return $this->productRepository->bulkDelete($ids);
        });
    }
}

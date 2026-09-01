<?php

namespace App\Application\Products\DTOs;

use Illuminate\Support\Str;

final class ProductData
{
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly float $price,
        public readonly ?float $salePrice,
        public readonly ?string $saleEndsAt,
        public readonly ?string $sku,
        public readonly int $stock,
        public readonly string $categoryId,
        public readonly bool $isActive = true,
        public readonly array $variants = [],
    ) {}

    public static function fromArray(array $data, ?self $existing = null): self
    {
        $name = $data['name'] ?? $existing?->name ?? '';
        return new self(
            name: $name,
            slug: array_key_exists('slug', $data) ? (string) $data['slug'] : ($existing?->slug ?: Str::slug($name)),
            description: $data['description'] ?? $existing?->description,
            price: array_key_exists('price', $data) ? (float) $data['price'] : ($existing?->price ?? 0),
            salePrice: array_key_exists('sale_price', $data) ? ($data['sale_price'] === null ? null : (float) $data['sale_price']) : $existing?->salePrice,
            saleEndsAt: array_key_exists('sale_ends_at', $data) ? $data['sale_ends_at'] : $existing?->saleEndsAt,
            sku: array_key_exists('sku', $data) ? (string) $data['sku'] : $existing?->sku,
            stock: array_key_exists('stock', $data) ? (int) $data['stock'] : ($existing?->stock ?? 0),
            categoryId: (string) ($data['category_id'] ?? $existing?->categoryId ?? ''),
            isActive: array_key_exists('is_active', $data) ? (bool) $data['is_active'] : ($existing?->isActive ?? true),
            variants: $data['variants'] ?? $existing?->variants ?? [],
        );
    }
}

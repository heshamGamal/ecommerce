<?php

namespace App\Domain\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['category_id', 'name', 'slug', 'short_description', 'description', 'price', 'compare_price', 'sale_price', 'sale_ends_at', 'sku', 'stock', 'is_active', 'is_featured'];
    protected $casts = ['price' => 'decimal:2', 'compare_price' => 'decimal:2', 'sale_price' => 'decimal:2', 'sale_ends_at' => 'datetime', 'stock' => 'integer', 'is_active' => 'boolean', 'is_featured' => 'boolean'];
    protected static function newFactory(): ProductFactory { return ProductFactory::new(); }

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function variants(): HasMany { return $this->hasMany(ProductVariant::class); }
    public function media(): MorphMany { return $this->morphMany(Media::class, 'mediable'); }
}

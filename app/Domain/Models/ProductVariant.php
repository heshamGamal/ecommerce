<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['product_id', 'attributes', 'sku', 'price', 'compare_price', 'stock_quantity', 'image', 'is_active'];
    protected $casts = ['attributes' => 'array', 'price' => 'decimal:2', 'compare_price' => 'decimal:2', 'stock_quantity' => 'integer', 'is_active' => 'boolean'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}

<?php

namespace App\Domain\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['name', 'slug', 'description', 'image', 'is_active', 'sort_order', 'parent_id'];
    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];
    protected static function newFactory(): CategoryFactory { return CategoryFactory::new(); }

    public function parent(): BelongsTo { return $this->belongsTo(self::class, 'parent_id'); }
    public function children(): HasMany { return $this->hasMany(self::class, 'parent_id'); }
    public function products(): HasMany { return $this->hasMany(Product::class); }
}

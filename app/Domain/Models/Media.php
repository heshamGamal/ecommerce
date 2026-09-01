<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'media';
    protected $fillable = ['file_path', 'file_name', 'mime_type', 'file_size', 'mediable_type', 'mediable_id', 'collection', 'sort_order', 'is_primary'];
    protected $casts = ['file_size' => 'integer', 'sort_order' => 'integer', 'is_primary' => 'boolean'];
    public function mediable(): MorphTo { return $this->morphTo(); }
}

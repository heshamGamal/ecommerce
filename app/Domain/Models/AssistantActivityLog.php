<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
class AssistantActivityLog extends Model
{
    use HasUuids;
    public $incrementing = false; public $timestamps = false; protected $keyType = 'string';
    protected $fillable = ['assistant_id','manager_id','action','specialty','outcome','duration_seconds','completed_at','entity_type','entity_id','metadata','created_at'];
    protected $casts = ['metadata' => 'array', 'duration_seconds' => 'integer', 'completed_at' => 'datetime', 'created_at' => 'datetime'];
    public function assistant() { return $this->belongsTo(AssistantProfile::class, 'assistant_id'); }
}

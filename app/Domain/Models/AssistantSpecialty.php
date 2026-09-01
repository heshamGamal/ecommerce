<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
class AssistantSpecialty extends Model
{
    use HasUuids;
    public $incrementing = false; protected $keyType = 'string';
    protected $fillable = ['assistant_id','specialty','is_primary'];
    protected $casts = ['is_primary' => 'boolean'];
    public function assistant() { return $this->belongsTo(AssistantProfile::class, 'assistant_id'); }
}

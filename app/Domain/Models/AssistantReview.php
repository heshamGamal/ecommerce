<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
class AssistantReview extends Model
{
    use HasUuids;
    public $incrementing = false; protected $keyType = 'string';
    protected $fillable = ['assistant_id','manager_id','rating','comment','reviewed_for'];
    protected $casts = ['rating' => 'integer', 'reviewed_for' => 'date'];
    public function assistant() { return $this->belongsTo(AssistantProfile::class, 'assistant_id'); }
}

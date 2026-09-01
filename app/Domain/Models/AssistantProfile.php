<?php
namespace App\Domain\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
class AssistantProfile extends Model
{
    use HasUuids;
    public $incrementing = false; protected $keyType = 'string';
    protected $fillable = ['user_id','manager_id','title','status','average_rating'];
    protected $casts = ['average_rating' => 'decimal:2'];
    public function user() { return $this->belongsTo(User::class); }
    public function manager() { return $this->belongsTo(User::class, 'manager_id'); }
    public function activities() { return $this->hasMany(AssistantActivityLog::class, 'assistant_id'); }
    public function reviews() { return $this->hasMany(AssistantReview::class, 'assistant_id'); }
    public function specialties() { return $this->hasMany(AssistantSpecialty::class, 'assistant_id'); }
}

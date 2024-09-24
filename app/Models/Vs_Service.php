<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Vs_Service extends Model
{
    use HasFactory;
    protected $table = 'vs_services';
	protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'status' => 'boolean',
    ];
    
    public function User_Commissions()
    {
        return $this->hasMany(User_Commission::class, 'service_id');
    }
}
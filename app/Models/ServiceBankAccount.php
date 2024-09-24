<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ServiceBankAccount extends Model
{
    use HasFactory;
    protected $table = 'vs_service_bank_accounts';
	protected $guarded = [];

    public function Service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
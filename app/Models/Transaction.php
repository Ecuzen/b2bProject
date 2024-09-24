<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Transaction extends Model
{
    protected $table = 'vs_transactions';
	protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'amount' => 'string',
        'status' => 'string',
        'message' => 'string',
        'user_id' => 'integer',
        'response' => 'string',
        'txn_type' => 'string',
        'related_id' => 'integer',
        'ack_file_name' => 'string',
        'computation_file_name' => 'string',
    ];

    // public function Certificate()
    // {
    //     return $this->hasMany(Certificate::class, 'service_form_id','related_id');
    // }

    // public function Files()
    // {
    //     return $this->hasMany(TransactionFile::class, 'transaction_id');
    // }

    public function ServiceForms()
    {
        return $this->belongsTo(ServiceForm::class, 'related_id');
    }

    // public function Company()
    // {
    //     return $this->hasMany(Company::class, 'service_form_id','related_id')->where('status',1);
    // }
}
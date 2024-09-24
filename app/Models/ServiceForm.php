<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ServiceForm extends Model
{
    use HasFactory;
    protected $table = 'vs_service_forms';
	protected $guarded = [];

    public function Service()
    {
        return $this->belongsTo(Vs_Service::class, 'service_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Business()
    {
        return $this->hasOne(Business::class, 'service_form_id');
    }

    public function Income()
    {
        return $this->hasOne(Income::class, 'service_form_id');
    }

    public function BankAccount()
    {
        return $this->hasMany(ServiceBankAccount::class, 'service_form_id');
    }

    public function Investment()
    {
        return $this->hasMany(Investment::class, 'service_form_id');
    }

    public function Trademark()
    {
        return $this->hasMany(Trademark::class, 'service_form_id');
    }

    public function Company()
    {
        return $this->hasMany(Company::class, 'service_form_id');
    }

    public function Members()
    {
        return $this->hasMany(Member::class, 'service_form_id')->where('type','Director');
    }

    public function SaleBills()
    {
        return $this->hasMany(ServiceBill::class, 'service_form_id')->where('file_type','Sales');
    }

    public function PurchaseBills()
    {
        return $this->hasMany(ServiceBill::class, 'service_form_id')->where('file_type','Purchases');
    }

    public function Partners()
    {
        return $this->hasMany(Member::class, 'service_form_id')->where('type','Partner');
    }

    public function GstFile()
    {
        return $this->hasMany(GstFile::class, 'service_form_id');
    }

    public function Certificate()
    {
        return $this->hasMany(Certificate::class, 'service_form_id');
    }

    public function Files()
    {
        return $this->hasMany(File::class, 'service_form_id');
    }
    
    public function SalarySlip()
    {
        return $this->hasMany(SalarySlip::class, 'service_form_id');
    }
    
    public function Transaction()
    {
        return $this->hasOne(Transaction::class, 'related_id');
    }
    
    
    
}
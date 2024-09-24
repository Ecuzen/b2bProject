<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AadharKycDetail extends Model
{
    use HasFactory;
    protected $table = 'aadhar_kyc_details';
    protected $guarded = [];
}

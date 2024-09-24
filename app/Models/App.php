<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;
    protected $table = 'app';
    protected $fillable = [
        'uid',
        'temp_key' ,
        'phone' ,
        'method' ,
        'otp' ,
        'imei' ,
        'device' ,
        'lat' ,
        'log' ,
        'ip',
    ];
}

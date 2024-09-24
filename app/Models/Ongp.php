<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ongp extends Model
{
    use HasFactory;
    protected $guarded=[];
    function __construct(){
        $this->attributes=[
            'skey'=>Str::uuid()
            ];
    }
}


<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Investment extends Model
{
    use HasFactory;
    protected $table = 'vs_investments';
	protected $guarded = [];
}
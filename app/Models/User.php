<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'pin',
        'phone',
        'role',
        'package',
         'owner'
    ];

    public function findUserOrFail($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['status' => 'success', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 202);
        }
    }
    public static function findUserByForeignKey($foreignKey)
    {
        if (!is_numeric($foreignKey)) {
            $column = 'email';
        }
        else
        {
            $column = 'phone';
        }
        try {
            $user = User::where($column, $foreignKey)->first();
            return response()->json(['status' => 'success', 'user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 202);
        }
    }
    function kyc():HasOne{
        return $this->hasOne(Kyc::class,'uid','id');
    }
}

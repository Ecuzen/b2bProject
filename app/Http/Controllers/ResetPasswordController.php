<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password; // Import the Password facade
use DB;
use Illuminate\Support\Str;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function index($type, $token)
    {
        $blockedUsers = ['password', 'pin'];
         if (!in_array($type, $blockedUsers)) {
             return abort(404);
        }
        $userTokenRow = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$userTokenRow) {
            return "Invalid token";
        }
        $user = DB::table('users')->where('email',$userTokenRow->email)->first();
        DB::table('password_reset_tokens')->where('token', $token)->delete();
        if($type == 'password')
        {
            $tempPass = Str::random(8);
            DB::table('users')->where('email',$user->email)->update(['password'=>$tempPass]);
        }
        else
        {
            $tempPass = rand(1000,9999);
            DB::table('users')->where('email',$user->email)->update(['pin'=>$tempPass]);
        }
        $data = [
            'view' => 'mail.password.tempPass',
            'subject' => $type.' reset request',
            'logo' => DB::table('settings')->where('name','logo')->first()->value,
            'company' => DB::table('settings')->where('name','title')->first()->value,
            'type' => ucfirst($type),
            'temp_password' => $tempPass
            ];
        Mail::to($user->email)->send(new SendEmail($data));
        return view('mail.password.resetSuccess',['type'=>$type]);
    }
}

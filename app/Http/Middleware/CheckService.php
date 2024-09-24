<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CheckService
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(session('uid') == null)
        {
            return Redirect::route('user.login');
        }
        
        if(Session::has('uid'))
        {
            $user = DB::table('users')->where('id',session('uid'))->first();
            $settingDetails = DB::table('settings')->pluck('value','name')->toArray();
            $fav = $settingDetails['favicon'];
            $title = $settingDetails['title'];
            $logo = $settingDetails['logo'];
            $wallet = $user->wallet;
            $role = $user->role;
             $vs_services = \App\Models\Vs_Service::where('status',1)->select([
            'id',
            'title',
            'file_name',
            'rate',
            'commission',
            'market_price',
            DB::raw("CONCAT('https://vyaparsamadhan.co.in/public/uploads/services/', file_name) as file_full_url")
        ])->get();
            $data = array(
                'walletBalance' => round($wallet,2),
                'UserRole' => $role,
                'customerName'=>$user->name,
                'customerEmail'=>$user->email,
                'user'=>$user,
                'customerRole'=>DB::table('role')->where('id',$user->role)->first()->name,
                'profileImage' => $user->profile,
                'fav' => $fav,
                'title' => $title,
                'logo' => $logo,
                'vs_services' => $vs_services,
                );
            view()->share($data);
            #check for kyc
            $checkRow = DB::table('kyc')->where('uid',session('uid'))->count();
            if($checkRow < 1)
            return Redirect::route('kyc-form');
            #check for pending
            $checkStatus = DB::table('kyc')->where('uid',session('uid'))->first();
            if($checkStatus->active != 1)
            return Redirect::route('pendingk');
        }
        $request->merge(['req' => 'web']);
        $request->setUserResolver(function () use ($user) {
                return $user;
            });
        $checkStatus = DB::table('users')->where('id',session('uid'))->first();
        view()->share('userRole',$checkStatus->role);
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class AndroidValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $api_key = $request->header('headerKey');
        $api_secret = $request->header('headerToken');
        if($api_key == "" || $api_secret =="")
        {
            return response()->json(['status'=>'ERROR','message'=>'Please share all header data!','error'=>true]);
        }
        $loged = DB::table('app')->where('api_secret',$api_secret)->where('api_key',$api_key)->first();
        $request->appdata=$loged;
        if($loged==null){
            return response()->json(['status'=>'ERROR','message'=>'Invalid session','error'=>true],403);
        }else{
            // $user = DB::table('app')->where('api_secret',$api_secret)->where('api_key',$api_key)->first();
            $user=\App\Models\User::find($loged->uid);
            if($user==null){
                return response()->json(['status'=>'ERROR','message'=>'Invalid user account','error'=>true],403);
            }
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            $request->merge(['uid' => $user->id,'req' => 'api']);
        }
        return $next($request);
    }
    
    
}

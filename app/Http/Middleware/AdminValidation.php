<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use DB;
use App\Models\EmployeeValidation;
use App\Models\AdminModel;

class AdminValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('aid') != null) {
            $currentRouteName = Route::currentRouteName();
            $getServiceName = DB::table('routeValidation')->where('route',$currentRouteName)->first();
            //  print_r($getServiceName) ;die;
            $vs_services = \Illuminate\Support\Facades\DB::table('vs_services')->where('status',1)->get();
            if($getServiceName != "" || $getServiceName != null)
            {
                $name = $getServiceName->name;
                $admin = AdminModel::find(session('aid'));
                $adminId = $admin->id;
                $EmployeeValidation = EmployeeValidation::where('aid',$adminId)->first();
                $checkRoute = $EmployeeValidation->$name;
                if($checkRoute != 1)
                return redirect()->route('unauthorize');
            }
            $totalUserAmount = DB::table('users')->sum('wallet');
            $adminName = DB::table('admin')->where('id', session('aid'))->value('name');
            $data = array(
                'adminName' => $adminName,
                'totalUserAmount' => round($totalUserAmount,2),
                'vs_services' =>$vs_services
                );
            view()->share($data);
            return $next($request);
        }
        return redirect()->route('admin'); 
    }
}
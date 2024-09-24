<?php
namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Service_Form extends Controller
{
    public function index($service_id = null)
    {
        if($service_id == null || empty($service_id))
        {
            return  redirect()->route('home')->with('error',"Service id is required!!");
        }
        
        $result = DB::table('bbps_categories')->orderBy('cat_key','asc')->get();
        return view('bbps-new.index',['results'=>$result]);
    }
    
}

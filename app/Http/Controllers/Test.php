<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Http;

class Test extends Controller
{
    public function index()
    {
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value; 
        $data = ['title' => $title,'logo' => $logo];
        return view('kyc.aadhar',$data);
    }
    
    /*public function index()
    {
    $api_key = DB::table('settings')->where('name','api_key')->first()->value;
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://partner.ecuzen.in/new/api/aeps/banks',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => array(
        'api-key: '.$api_key
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    $response = json_decode($response);
    $bankList = $response->banks;
    foreach ($bankList as $bank)
    {
        DB::table('banks')->insert(['bid' => $bank->bid,'name' => $bank->bank_name]);
    }
    }*/
    
    
    /* public function index(){
   
$tablesToBeTruncated = [
    'coponcharge','dmtcharge','icaepscomission','icapcharge','icdpcomission','mscom','payoutchargeimps','comissionv2','payoutchargeneft','bbps','qtransfer','accountVerify',
    'app',
    'aepskyc',
    'bank',
    'app_dmt',
    'bbpstxn',
    'bbps_data',
    'dmrtxn',
    'iaepstxn',
    'kyc',
    'loginlog',
    'otherservices',
    'pancoupontxn',
    'payoutaccount',
    'psa',
    'qtransfertxn',
    'qtra_acc',
    'qtra_otp',
    'rechargetxn',
    'register',
    'test',
    'ticket',
    'topup',
    'transaction',
    'users',
    'wallet',
    'package',
    'wallet_to_wallet',
];

foreach ($tablesToBeTruncated as $table) {
    DB::table($table)->truncate();
}
return "All specified tables have been truncated.";
} */
    
    
}

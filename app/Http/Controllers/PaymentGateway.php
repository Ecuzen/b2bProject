<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PaymentGateway extends Controller
{
    protected function generateQRCode()
    {
        $qrString = null;
        $user = DB::table('users')->where('id',session('uid'))->select('name','id')->get()[0];
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $headers = array('api-key' => $api_key);
        $url = 'https://partners.ecuzen.in/api/pg/fetch_qr';
        $response = $this->callApiwithHeader($url,'GET','',$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $qrString="upi://pay?mc=".$response->mcc."&pa=".$response->vpa."&pn=".$user->name."&tr=USERID".$user->id;
            DB::table('users')->where('id',session('uid'))->update(['qrString'=>$qrString]);
        }
        return $qrString;
    }
    
    
    public function index()
    {
        $checkUserQr = DB::table('users')->where('id',session('uid'))->pluck('qrString')[0];
        if($checkUserQr == null || $checkUserQr == "")
        $checkUserQr = $this->generateQRCode();
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        return view('paymentGateway.index',['logo'=>$logo,'title'=>$title,'string' => $checkUserQr]);
    }
    
    
    
    public function initiateTransaction(Request $res)
    {
        
        $validator = Validator::make($res->all(), [
            'amount' => 'required|numeric|gt:0',
            'name' => 'required|string',
            'upi' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERROR','message' => $validator->errors()->first()], 200);
        }
        $uid = session('uid');
        $amount = $res->amount;
        $name = $res->name;
        $upi = $res->upi;
        $sendingData = array(
            'payer_name' => $name,
            'payer_vpa' => $upi,
            'amount' => $amount,
            'expiry' => "1",
            'remark' => 'test'
            );
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $headers = array('api-key' => $api_key);
        $url = 'https://partners.ecuzen.in/api/pg/raise_collect_request';
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingData),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $insertdata = array(
                'uid' => $uid,
                'sid' => $response->data->sid,
                'txnid' => $response->data->txnid,
                'type' => $response->data->type,
                'amount' => $response->data->amount,
                'name' => $response->data->payer_name,
                'vpa' => $response->data->payer_vpa,
                'status' => $response->data->status,
                'message' => $response->msg,
                'response' => json_encode($response),
                );
            DB::table('pgTxn')->insert($insertdata);
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg]);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>$response->msg]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class Recharge extends Controller
{
    public function __construct()
    {
        if(session('uid') == null)
        {
            return redirect()->to('/');
        }
    }
    
    protected function getPlans($mobile)
    {
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = 'https://partner.ecuzen.in/new/api/recharge/fetchop';
        $response = $this->callApi($url,'POST',['api_key'=>$api_key,'mobile'=>$mobile],'');
        $data = json_decode($response);
        $sendingdata = array(
            'api_key' => $api_key,
            'circle' => $data->circle,
            'opid' => $data->opid,
            );
           
        $url = 'https://partner.ecuzen.in/new/api/recharge/plans';
        $response = $this->callApi($url,'POST',$sendingdata,'');
        return $response;
    }
    
    public function mobile()
    {
        // return print_r(json_decode($this->getPlans('7726828026')));
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $transactions = DB::table('rechargetxn')->orderBy('id', 'desc')->where('uid',session('uid'))->limit(5)->get();
        foreach ($transactions as $tData)
        {
            $tData->operator = DB::table('rechargeop')->where('id',$tData->operator)->first()->name;
        }
        $operators = DB::table('rechargeop')->where('type',1)->get();
        $data = ['title' => $title,'logo' => $logo,'transactions'=>$transactions,'operators'=>$operators];
        return view("recharge.mobile",$data);
    }
    
    public function fetchoperator(Request $res)
    {

        $mobile = $res->mobile;
        $url ="https://digitalapiproxy.paytm.com/v1/mobile/getopcirclebyrange?channel=web&version=2&number={$mobile}&child_site_id=1&site_id=1&locale=en-in"; 
        $response = $this->callApi($url,'GET','','');
        $resData = json_decode($response);
        // return $resData;
        if(!isset($resData->postpaid)){
            return response()->json(['status'=>'INFO','message'=>'Invalid number!!']);
        }
        $postpaid = $resData->postpaid;
        if($postpaid == 'true')
        return response()->json(['status'=>'INFO','message'=>'You entered is a postpaid number!!']);
        $operator =  strtoupper($resData->Operator);
        if($operator == 'VODAFONE IDEA')
        $operator = 'VI';
        #get data by op name
        $data = DB::table('rechargeop')->where('name', 'like', '%' . $operator . '%')->get();
        $id = $data->pluck('code');
        $plans = $this->getPlans($mobile);
        $plans = json_decode($plans);
        session()->put('plans',$plans);
        $fKey = "";
        foreach ($plans->data as $key=>$val)
        { 
            $fKey = $key;
            break;
        }
       
        $logo = $data->pluck('image');
        return response()->json(['status'=>'SUCCESS','id'=>$id[0],'plans'=>view('recharge.plans',['fKey'=>$fKey,'plans'=>$plans->data,'logo'=>$logo[0]])->render()]);
    }
    
    public function doRecharge(Request $res){
        $validator = Validator::make($res->all(), [
                'mobile' => 'required|numeric|digits:10',
                'amount' => 'required|numeric|gt:9',
                'operator' => 'required|numeric|gt:0'
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['status'=>'ERROR','message' => $errors->first()], 200);
        }
        $mobile = $res->mobile;
        $operator = $res->operator;
        $amount = $res->amount;
        $uid = session('uid');
        $check = $this->checkservice('recharge',$uid);
        if($check->status == 'ERROR')
        return $check;
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!']);
        $user = DB::table('users')->where('id',$uid)->first();
        $commission = DB::table('comissionv2')->where('package',$user->package)->where('operator',$operator)->first();
        if($commission == null)
        return response()->json(['status'=>'ERROR','message'=>'Commission are not set for you please contact to admin!']);
        if($commission->percent == 1)
        {
            $commission = ($amount * $commission->amount) /100;
        }
        else
        {
           $commission = $commission->amount;
        }
        #Before transaction do entry
        $tempTxnid = 'REC'.rand(1000000000,9999999999);
        $insdata = array(
                'uid' => $uid,
                'txnid' => $tempTxnid,
                'mobile' => $mobile,
                'type' => 'RECHARGE',
                'operator' => $operator,
                'amount' =>$amount,
                'status' => 'PENDING',
                'message' => 'NA',
                'response' => 'NA',
                );
        $this->insertdata('rechargetxn',$insdata);
        $this->debitEntry($uid,'RECHARGE',$tempTxnid,$amount);
        $this->creditEntry($uid,'RECHARGE-COMMISSION',$tempTxnid,round($commission,2));
        $url = 'https://partner.ecuzen.in/new/api/recharge/dorecharge';
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $hashn = $mobile.",".$operator.",".$amount.",".$api_key;
        $hash = hash('sha512',$hashn);
        $sendingdata = array(
            "api_key" => $api_key,
            "mobile" => $mobile,
            "opid" => $operator,
            "amount" => $amount,
            "hash" => $hash,
            'txnid' => $tempTxnid
            );
        $response = $this->callApi($url,'POST',json_encode($sendingdata),'p');
       
        // $response = '{"status":"SUCCESS","msg":"Transaction Successfull","txnid":"RE879323850N"}';
       $response = json_decode($response);
       $status = $response->status;
       if($status == "success" || $status == "SUCCESS" || $status == "PENDING" || $status == "pending")
       {
           $insdata = array(
                'uid' => $uid,
                'txnid' => $response->txnid,
                'mobile' => $mobile,
                'type' => 'RECHARGE',
                'operator' => $operator,
                'amount' =>$amount,
                'status' => $status,
                'message' => $response->msg,
                'response' => json_encode($response),
                );
        $this->updateData('rechargetxn','txnid',$tempTxnid,$insdata);
        $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$response->txnid]);
        $this->distributeComm($uid,'RECHARGE-COMMISSION','comissionv2',$amount,$response->txnid,$operator);
        $data = array(
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'txnid' => $response->txnid,
                'mobile' => $mobile,
                'operator' => DB::table('rechargeop')->where('id',$operator)->first()->name,
                'status' => $status,
                'item1' => 'Recharge',
                'item2' => 'Recharge Commission',
                'amount1' => $amount,
                'amount2' => $commission,
                'total' => $amount - $commission,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                );
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.recharge',$data)->render(),'closing'=>$wallet - $amount - $commission],200);
       }
       else
       {
           $insdata = array(
                'uid' => $uid,
                'txnid' => isset($response->txnid)?$response->txnid:$tempTxnid,
                'mobile' => $mobile,
                'type' => 'RECHARGE',
                'operator' => $operator,
                'amount' =>$amount,
                'status' => $status,
                'message' => $response->msg,
                'response' => json_encode($response),
                );
        $this->updateData('rechargetxn','txnid',$tempTxnid,$insdata);
        $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>isset($response->txnid)?$response->txnid:$tempTxnid]);
        $this->creditEntry($uid,'RECHARGE-REFUND',isset($response->txnid)?$response->txnid:$tempTxnid,$amount);
        $this->debitEntry($uid,'RECHARGE-COMMISSION-R',isset($response->txnid)?$response->txnid:$tempTxnid,$commission);
        return response()->json(['status'=>'ERROR','message'=>$response->msg]);
       }
        
    }
}

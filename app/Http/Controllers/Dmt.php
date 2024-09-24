<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class Dmt extends Controller
{
    public function __construct()
    {
        if(session('uid') == null)
        {
            return redirect()->to('/');
        }
    }
    function index()
    {
            $title = DB::table('settings')->where('name', 'title')->first()->value;
            $logo = DB::table('settings')->where('name', 'logo')->first()->value;
            $banklist = DB::table('dmtbanklist')->orderBy('bname', 'asc')->get();
            $data = ['title'=>$title,'logo'=>$logo,'bankList'=>$banklist];
            return view('dmt/index',$data);
    }
    
    public function login(Request $res)
    {
        $phone = $res->phone;
        $uid = session('uid');
        $check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return $check;
        $url = "https://partners.ecuzen.in/api/dmt/remitter_details";
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $headers = array('api-key' => $api_key);
        $sendingdata = ["mobile" => $phone];
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            if(!empty($response->info))
            {
                $limit1 = $response->info->bank1_limit??'0';
                $limit2 = $response->info->bank2_limit??'0';
                $limit3 = $response->info->bank3_limit??'0';
                session()->put('phone',$phone);
                $limit = $limit1 + $limit2 + $limit3;
                return response()->json(['status'=>'SUCCESS','activity'=>'dashboard','message'=>$response->msg,'view'=>view('dmt.panel',['data'=>$response->info,'limit'=>$limit])->render()]);
            }
            else
            {
                $data = ['mobile'=>$phone,'key'=>$response->stateresp];
                return response()->json(['status'=>'SUCCESS','activity'=>'register','message'=>$response->msg,'view'=>view('dmt.register',$data)->render()]);
            }
        }
        else 
        {
            return response()->json(['status'=>$response->status,'message'=>$response->msg]);
        }
    }
    public function register(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'fname' => 'required|string',
                'lname' => 'required|string',
                'otp' => 'required|numeric',
                'pincode' => 'required|numeric|digits:6',
                'address' => 'required|string',
                'dob' => 'required|date',
                'mobile' => 'required|numeric|digits:10',
                'key' => 'required|string'
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['status'=>'ERROR','data' => $errors], 200);
        }
        $uid = session('uid');
        $check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return $check;
        $firstname = $res->fname;
        $lastname = $res->lname;
        $pincode = $res->pincode;
        $otp = $res->otp;
        $dob = $res->dob;
        $address = $res->address;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partners.ecuzen.in/api/dmt/register";
        $sendingdata = [
            "mobile" => $res->mobile,
            'stateresp' => $res->key,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "pincode" => $pincode,
            "otp" => $otp,
            "dob" => $dob,
            "address" => $address,
        ];
        $headers = array('api-key' => $api_key);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg]);
        }
        else
        {
            $error= array();
            $error[] = $response->msg;
            return response()->json(['status'=>'ERROR','data'=>array('message'=>$error)]);
        }
    }
    
    public function dmtData()
    {
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $phone = session('phone');
        $url = "https://partners.ecuzen.in/api/dmt/fetchbene";
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $headers = array('api-key' => $api_key);
        $sendingdata = ["mobile" => $phone];
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        // return $response;
        if($response->status == 'SUCCESS')
        return response()->json(['status'=>'SUCCESS','view'=>view('dmt.accountData',['data'=>$response->data])->render(),'message'=>$response->msg]);
        else
        return response()->json(['status'=>$response->status,'message'=>$response->msg]);
    }
    
    public function addaccount(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'name' => 'required|string',
                'account' => 'required|numeric',
                'ifsc' => 'required|string',
                'bid' => 'required|numeric',
                'dob' => 'required|date|before:today',
                'pincode' => 'required|numeric|digits:6',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['status'=>'ERROR','data' => $errors], 200);
        }
        $uid = session('uid');
        $check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'INFO','message'=>$check->message]);
        $phone = session('phone');
        $name = $res->name;
        $account = $res->account;
        $ifsc = $res->ifsc;
        $bid = $res->bid;
        $dob = $res->dob;
        $pincode = $res->pincode;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partners.ecuzen.in/api/dmt/regben";
        $sendingdata =array(
            "mobile" =>$phone,
            "benename" =>$name,
            "bankid" =>$bid,
            "accno" =>$account,
            "pincode" =>$pincode,
            "dob" =>$dob,
            "ifsccode" => $ifsc
            );
        $headers = array('api-key' => $api_key);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        return response()->json(['status'=>'SUCCESS','message'=>$response->msg]);
        else
        return response()->json(['status'=>'ERROR','message'=>$response->msg]);
    }
    public function dmtLogout()
    {
        session()->forget('phone');
        return redirect()->route('dmt');
    }
    public function deleteAccount($account)
    {
        $uid = session('uid');
        $check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message]);
        $phone = session('phone');
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partners.ecuzen.in/api/dmt/delbene";
        $sendingdata = [
            "mobile" => $phone,
            "bene_id" => $account,
        ];
        $headers = array('api-key' => $api_key);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        return response()->json(['status'=>'SUCCESS','message'=>$response->msg]);
        else
        return response()->json(['status'=>'ERROR','message'=>$response->msg]);
    }
    public function initiateTransaction(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'mode' => 'required|string',
                'bene_id' => 'required|numeric',
                'amount' => 'required|numeric|gt:0',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','data' => $errors], 200);
        }
        $uid = session('uid');
        $check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message]);
        $mode = $res->mode;
        $bene_id = $res->bene_id;
        $amount= $res->amount;
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'INFO','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],200);
        $user = DB::table('users')->where('id',$uid)->first();
        $charge = $this->checkcharge($amount,'dmtcharge',$user->package);
        if($charge != true)
        return response()->json(['status'=>'INFO','message'=>'Charges are not set for you please contact to admin!']);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'INFO','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction']);
        $maxlimit = $this->checkmaxlimit('dmtcharge',$user->package);
        if($maxlimit != true)
        return response()->json(['status'=>'INFO','message'=>'Unable to get your maximum Transaction limit please contact to admin!!']);
        if($amount > $maxlimit)
        return response()->json(['status'=>'INFO','message'=>'Your maximum transaction limit is '.$maxlimit],200);
         $ifsc  =  $res->ifsc;
         $txnVia = DB::table('settings')->where('name', 'txnvia')->first()->value;
        if($txnVia != 'pin')
        {
            $otp = rand(100000,999999);
            $sms_api = DB::table('settings')->where('name','sms_api')->first()->value;
            $senderid = "ECZSPL";
            $message = "Welcome To B2B Software Dear " . $user->name . " Your OTP For Amount " . $amount . " Is " . $otp . "	";
            $link = "https://india1sms.com/api/smsg/sendsms?APIKey=".urlencode($sms_api)."&senderid=".urlencode($senderid)."&number=".urlencode($user->phone)."&message=".urlencode($message)."";
            $res = $this->callApi($link,'POST','','');
            $res = json_decode($res);
            if($res->status != 'SUCCESS')
            return response()->json(['status'=>$res->status,'message'=>$res->message]);
        }
        else
        {
            $otp = $user->pin;
        }
        $data = array(
            "amount" => $amount,
            'mode' => $mode,
            'bene_id' => $bene_id,
            'otp' => $otp,
            'ifsc' =>  $ifsc,
            );
        
        session()->put('txndata',$data);
        if($txnVia != 'pin')
        return response()->json(['status'=>'SUCCESS','view'=>view('transaction.otpNew')->render(),'message'=>'OTP send to your register mobile number']);
        else
        return response()->json(['status'=>'SUCCESS','view'=>view('transaction.pinview')->render(),'message'=>'Enter your PIN']);
    }
    
    public function doTransaction(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'otp' => 'required|numeric|digits_between:3,6',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','data' => $errors], 200);
        }
        $uid = session('uid');
        $user = User::find($uid);
        $check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message]);
        $txndata = session('txndata');
        $mode = $txndata['mode'];
        $bene_id = $txndata['bene_id'];
        $amount= $txndata['amount'];
        $otp = $res->otp;
        $sessionotp = $txndata['otp'];
        if($otp != $sessionotp)
        return response()->json(['status'=>'ERROR','message'=>'Invalid OTP']);
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'INFO','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],200);
        $charge = $this->checkcharge($amount,'dmtcharge',$user->package);
        if($charge != true)
        return response()->json(['status'=>'INFO','message'=>'Charges are not set for you please contact to admin!']);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'INFO','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction']);
        $maxlimit = $this->checkmaxlimit('dmtcharge',$user->package);
        if($maxlimit != true)
        return response()->json(['status'=>'INFO','message'=>'Unable to get your maximum Transaction limit please contact to admin!!']);
        if($amount > $maxlimit)
        return response()->json(['status'=>'INFO','message'=>'Your maximum transaction limit is '.$maxlimit],200);
        $phone = session('phone');
        #Before transaction do entry
        $tempTxnid = 'DMT'.rand(1000000000,9999999999);
        $insdata = array(
                'uid' => $uid,
                'tempid' => $tempTxnid,
                'amount' =>$amount,
                'status' => 'PENDING',
                'mode' => $mode,
                'message' => 'NA',
                'response' => 'NA',
                'utr' => 'NA',
                'ifsc' => $txndata['ifsc'],
                );
        $this->insertdata('dmrtxn',$insdata);
        $this->debitEntry($uid,'DMT',$tempTxnid,$amount);
        $this->debitEntry($uid,'DMT-CHARGE',$tempTxnid,$charge);
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $callbackUrl = env('APP_URL').'/api/dmt-callback';
        $sendingdata = array(
            "mobile"=>$phone,
            "bene_id"=>$bene_id,
            "amount"=>(double)$amount,
            "mode"=>$mode,
            "ref_key"=>$tempTxnid
            );
        $url = 'https://partners.ecuzen.in/api/dmt/dotxn';
        $headers = array('api-key' => $api_key,'callback-url'=>$callbackUrl);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        // $response = '{"status":"SUCCESS","msg":"Transaction is on hold","data":{"status":true,"response_code":1,"ackno":"68164981","referenceid":"DMT426030709312","utr":"0","txn_status":4,"benename":"","remarks":"timeout - ","message":"Transaction is on hold","remitter":"8696921014","account_number":"677105601578","bc_share":"2.5","txn_amount":"100","NPCI_response_code":"500","bank_status":"no","balance":928.1299999999999954525264911353588104248046875,"customercharge":"10.00","gst":"1.53","tds":"0.30","netcommission":"5.67"}}';
        $response = json_decode($response);
        if($response->status == 'SUCCESS' || $response->status == 'PENDING')
        {
            $txnData = $response->data;
            $updateData = array(
                'txnid' => $txnData->referenceid,
                'account' => $txnData->account_number,
                'bname' => $txnData->benename,
                'status' => $response->status,
                'message' => $response->msg,
                'utr' => $txnData->utr,
                'response' => json_encode($response)
                );
            $this->updateData('dmrtxn','tempid',$tempTxnid,$updateData);
            $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$txnData->referenceid]);
            $this->distributeCommission($uid,'DMT-Commission','dmtcharge',$amount,$txnData->referenceid);
            $data = array(
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => $txnData->benename,
                'txnid' => $txnData->referenceid,
                'account' => $txnData->account_number,
                'ifsc' => $txndata['ifsc'],
                'status' => $response->status,
                'item1' => 'DMT',
                'item2' => 'DMT-CHARGE',
                'amount1' => $amount,
                'amount2' => $charge,
                'total' => $amount+$charge,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                );
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.dmt',$data)->render(),'closing'=>$wallet - $amount+$charge],200);
        }
        else
        {
            $responseData = $response->data??null;
            $insdata = array(
                'txnid' => $responseData->referenceid??$tempTxnid,
                'account' => $responseData->account_number??'NA',
                'ifsc' => $txndata['ifsc'],
                'bname' => $responseData->benename??'NA',
                'amount' =>$amount,
                'status' => $response->status,
                'message' => $response->msg,
                'response' => json_encode($response),
                'utr' => $responseData->utr??'NA',
                'mode' => $mode,
                );
            $this->updateData('dmrtxn','tempid',$tempTxnid,$insdata);
            $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$responseData->referenceid??$tempTxnid]);
            $this->creditEntry($uid,'DMT-REFUND',$responseData->referenceid??$tempTxnid,$amount+$charge);
            return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false]);
        }
    }
    public function dmtAccountVerify(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'accno' => 'required|numeric',
                'bankid' => 'required|numeric',
                'bene_id' => 'required|numeric',
                'benename' => 'required|string',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','data' => $errors], 200);
        }
        $uid = session('uid');
        $user = User::find($uid);
        $wallet = $this->checkwallet($uid);
        $charge = DB::table('accountVerify')->where('package',$user->package)->first()->amount;
        if($wallet < $charge)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!']);
        $accno = $res->accno;
        $bankid = $res->bankid;
        $bene_id = $res->bene_id;
        $benename = $res->benename;
        $phone = session('phone');
        $sendingData = array(
            "accno" => $accno,
            "mobile" => $phone,
            "bene_id" => $bene_id,
            "bankid" => $bankid,
            "benename" => $benename,
            );
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $callbackUrl = env('APP_URL').'/api/dmt-account-verify-callback';
        $url = 'https://partners.ecuzen.in/api/dmt/penydrop';
        $headers = array('api-key' => $api_key,'callback-url'=>$callbackUrl);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingData),$headers);
        // $response = '{"status":"SUCCESS","msg":"na","data":{"status":true,"response_code":1,"utr":"328911100371","ackno":74144905,"txn_status":1,"benename":"ECUZEN SOFTWARE PRIV","message":"Transaction Successful","balance":12462.59000000000014551915228366851806640625,"refid":"DMT345607824019"}}';
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $insertData = array(
                'uid' => $uid,
                'txnid' => $response->refid,
                'account' => $accno,
                'ifsc' => null,
                'name' => $response->benename, 
                'rrn' => $response->utr,
                'response' => json_encode($response)
                );
            $this->insertdata('accountVerifyTxn',$insertData);
            $this->debitEntry($uid,'ACC-VERIFY',$response->refid,$charge);
            return response()->json(['status'=>'SUCCESS','message'=>'Account verified successfully!!','name'=>$response->bname]);
        }
        else
        return response()->json(['status'=>'ERROR','message'=>$response->msg]);
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class PayoutController extends Controller
{
    public function __construct()
    {
        if(session('uid') == null)
        {
            return redirect()->to('/');
        }
    }
    public function index()
    {
        if(null !==(session()->get('uid'))){
            $title = DB::table('settings')->where('name', 'title')->first()->value;
            $logo = DB::table('settings')->where('name', 'logo')->first()->value;
            $accounts = DB::table('payoutaccount')->where('uid',session('uid'))->get();
            $data = ['title' => $title,'logo' => $logo,'accounts'=>$accounts];
            return view("payout/panel",$data);
        }else{
            return redirect('/');
        }
    }
    public function addaccount(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'name' => 'required|string',
                'account' => 'required|numeric|digits_between:10,20',
                'ifsc' => 'required|string|min:10',
                'passbook' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','data' => $errors], 200);
            }
        #check for account 
        $account = $res->account;
        $check = DB::table('payoutaccount')->where('account',$account)->count();
        if($check > 0)
        return response()->json(['status'=>'ERROR','data'=>['msg'=>['Account already exists']]]);
        if ($res->hasFile('passbook')) {
                $image = $res->file('passbook');
                $imageName = 'payout'.time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('images', $imageName, 'public');
                $imagePath = Storage::disk('public')->path('images/' . $imageName);
                $imagePath = str_replace('/www/wwwroot/','https://',$imagePath);
            }
            $name = $res->name;
            $ifsc = $res->ifsc;
        $data = array(
            "uid" => session('uid'),
            "name" => $name,
            "account" => $account,
            "ifsc" => $ifsc,
            "passbook" => $imagePath,
            "status" => 'PENDING',
            );
        $res = $this->insertdata('payoutaccount',$data);
        if($res != true)
        return response()->json(['status'=>'ERROR','data'=>['msg'=>['Database error']]],500);
        $accounts = DB::table('payoutaccount')->where('uid',session('uid'))->get();
        return response()->json(['status'=>'SUCCESS','message'=>'Account added successfully!! Please wait untill admin approve your account!!','view'=>view('payout.refresh',['accounts'=>$accounts])->render()],200);
    }
    public function delaccount(Request $res)
    {
        $id = $res->id;
        #time to delete existing image
        $imageUrl = DB::table('payoutaccount')->where('id',$id)->first()->passbook;
        $filename = basename($imageUrl);
        $filePath = '/storage/images/' . $filename;
        if (Storage::disk('public')->exists($filePath))
        Storage::disk('public')->delete($filePath);
        $res = DB::table('payoutaccount')->where('id',$id)->delete();
        if($res)
        return response()->json(['status'=>'SUCCESS','message'=>'Account deleted successfully!! Please refresh page to see latest data'],200);
        return response()->json(['status'=>'ERROR','Database error'],500);
    }
    public function initiate(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'mode' => [
                Rule::in(['IMPS', 'NEFT']),
                ],
                'mobile' => 'required|numeric|digits:10',
                'bid' => 'required|numeric|gt:0',
                'amount' => 'required|numeric|gt:0',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','data' => $errors], 200);
            }
        $uid = session('uid');
        $check = $this->checkservice('payout',$uid);
        if($check->status == 'ERROR')
        return $check;
        $bid = $res->bid;
        $mode = $res->mode;
        $amount = $res->amount;
        $mobile = $res->mobile;
        #check account is approved or not
        $accCheck = DB::table('payoutaccount')->where('id',$bid)->first()->status;
        if($accCheck != 'APPROVED')
        return response()->json(['status'=>'ERROR','data'=>['message'=>['The account you are using is not approved']]]);
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'INFO','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],200);
        $user = DB::table('users')->where('id',$uid)->first();
        $table = $mode == 'IMPS'?'payoutchargeimps':'payoutchargeneft';
        $charge = $this->checkcharge($amount,$table,$user->package);
        if($charge != true)
        return response()->json(['status'=>'INFO','message'=>'Charges are not set for you please contact to admin!']);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'INFO','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction']);
         $maxlimit = $this->checkmaxlimit($table,$user->package);
        if($maxlimit != true)
        return response()->json(['status'=>'INFO','message'=>'Unable to get your maximum Transaction limit please contact to admin!!']);
        if($amount > $maxlimit)
        return response()->json(['status'=>'INFO','message'=>'Your maximum transaction limit is '.$maxlimit],200);
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
            'otp' => $otp,
            'mobile' => $mobile,
            'bid' => $bid,
            'mode' => $mode,
            );
        session()->put('txndata',$data);
        if($txnVia != 'pin')
        return response()->json(['status'=>'SUCCESS','view'=>view('transaction.otpNew')->render(),'message'=>'OTP send to your register mobile number']);
        else
        return response()->json(['status'=>'SUCCESS','view'=>view('transaction.pinview')->render(),'message'=>'Enter your PIN']);
    }
    public function transaction(Request $res)
    {
        $otp = $res->otp;
        $txnData = session('txndata');
        if($txnData['otp'] != $otp)
        return response()->json(['status'=>'INFO','message'=>'Invalid OTP']);
        $uid = session('uid');
        $check = $this->checkservice('payout',$uid);
        if($check->status == 'ERROR')
        return $check;
        $amount = $txnData['amount'];
        $bid = $txnData['bid'];
        $mode = $txnData['mode'];
        $mobile = $txnData['mobile'];
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!']);
        $user = DB::table('users')->where('id',$uid)->first();
        $table = $mode == 'IMPS'?'payoutchargeimps':'payoutchargeneft';
        $charge = $this->checkcharge($amount,$table,$user->package);
        if($charge != true)
        return response()->json(['status'=>'ERROR','message'=>'Charges are not set for you please contact to admin!']);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction']);
        $accountDetails = DB::table('payoutaccount')->where('id',$bid)->count();
        if($accountDetails != 1)
        return response()->json(['status'=>'ERROR','data'=>['message'=>['Accounts details are incorrect!!']]]);
        $accountDetails = DB::table('payoutaccount')->where('id',$bid)->first();
        $account = $accountDetails->account;
        $ifsc = $accountDetails->ifsc;
        $name = $accountDetails->name;
        
        #Before transaction do entry
        $tempTxnid = 'PYOT'.rand(1000000000,9999999999);
        $insdata = array(
                'uid' => $uid,
                'txnid' => $tempTxnid,
                'account' => $account,
                'ifsc' => $ifsc,
                'bname' => $name,
                'amount' =>$amount,
                'status' => 'PENDING',
                'message' => 'NA',
                'response' => 'NA',
                'rrn' => 'NA',
                'mode' => $mode,
                );
            $this->insertdata('payouttxn',$insdata);
            $this->debitEntry(session('uid'),'Payout',$tempTxnid,$amount);
            $this->debitEntry(session('uid'),'Payout-charge',$tempTxnid,$charge);
        
        
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $hashn = $account.",".$ifsc.",".$mode.",".$amount.",".$api_key;
	    $hash = hash('sha512',$hashn);
        $sendingdata = [
            "api_key" => $api_key,
            "account_no" => $account,
            "ifsc" => $ifsc,
            "name" => $name,
            "mode" => $mode,
            "amount" => $amount,
            "hash" => $hash,
            "txnid" => $tempTxnid
            ];
        $sendingdata = json_encode($sendingdata);
        $url = "https://partner.ecuzen.in/new/api/payout/dopayout";
        $response = $this->callApi($url,'POST',$sendingdata,[]);
        #for dummy response
       /* $response = '{"status":"SUCCESS","txnid":"PYOT686062722598898","amount":1,"rrn":"315812798064","msg":"Transaction SUCCESS","account":"555710510003414","ifsc":"BKID0005557","name":"Vinay","mode":"IMPS"}';*/
        $response = json_decode($response,true);
       
        $status =  $response['status'];
        if($status=="SUCCESS" || $status=="success" || $status=="PENDING" || $status=="IN_PROCESS" || $status == "accepted" || $status == "ACCEPTED")
        {
            $insdata = array(
                'uid' => session('uid'),
                'txnid' => $response['txnid'],
                'account' => $response['account'],
                'ifsc' => $response['ifsc'],
                'bname' => $response['name'],
                'amount' =>$response['amount'],
                'status' => $status,
                'message' => $response['msg'],
                'response' => json_encode($response),
                'rrn' => $response['rrn'],
                'mode' => $response['mode'],
                );
            $this->updateData('payouttxn','txnid',$tempTxnid,$insdata);
            $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$response['txnid']]);
            $this->distributeCommission($uid,'PYT-Commission',$table,$amount,$response['txnid']);
            #check for existance account
            $data = array(
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => $name,
                'txnid' => $response['txnid'],
                'account' => $account,
                'ifsc' => $ifsc,
                'status' => $status,
                'item1' => 'Payout',
                'item2' => 'Payout Charge',
                'amount1' => $amount,
                'amount2' => $charge,
                'total' => $amount+$charge,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                'mode' => $response['mode']
                );
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.payout',$data)->render(),'closing'=>$wallet - $amount+$charge],200);
        }
        else
        {
            $txnid = isset($response['txnid']) ? $response['txnid'] : $tempTxnid;
            $insdata = array(
                'uid' => session('uid'),
                'txnid' => $txnid,
                'account' => $account,
                'ifsc' => $ifsc,
                'bname' => $name,
                'amount' =>$amount,
                'status' => $status,
                'message' => $response['msg'],
                'response' => json_encode($response),
                'rrn' => isset($response['rrn']) ? $response['rrn'] : "NA",
                'mode' => isset($response['mode']) ? $response['mode'] : "NA"
                );
            // $this->insertdata('payouttxn',$insdata);
            $this->updateData('payouttxn','txnid',$tempTxnid,$insdata);
            $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$txnid]);
            $this->creditEntry($uid,'PAYOUT-REFUND',$txnid,$amount+$charge);
            return response()->json(['status'=>'ERROR','message'=>$response['msg']]);
        }
    }
}

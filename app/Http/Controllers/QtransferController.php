<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class QtransferController extends Controller
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
            $global = DB::table('global_ifsc_list')->select('name','ifscGlobal')->get();
            $transactions = DB::table('qtransfertxn')->orderBy('id', 'desc')->where('uid',session('uid'))->limit(5)->get();
            $data = ['title' => $title,'logo' => $logo,'global'=>$global,'transactions'=>$transactions];
            return view("qtransfer/panel",$data);
        }else{
            return redirect('/');
        }
    }
    
    
    public function fetch(Request $res)
    {
        $mobile = $res->mobile;
        $result = DB::table('qtra_acc')->where('mobile',$mobile)->get();
        if($result->count() > 0)
        return response()->json(['status'=>'SUCCESS','view'=>view('qtransfer.fetchacc',['result'=>$result])->render(),'message'=>'Some accounts fetched','head'=>'Select account associated with this number'],200);
        else
        return response()->json(['status'=>'INFO','message'=>'No accounts are associated with this number'],200);
    }
    public function initiate(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'amount' => 'required|numeric|gt:0',
                'account' => 'required|numeric|min:10',
                'ifsc' => 'required|string|min:10',
                'mobile' => 'required|numeric|min:10',
                'name' => 'required|string'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','data' => $errors], 200);
            }
        $uid = session('uid');
        $check = $this->checkservice('qtransfer',$uid);
        if($check->status == 'ERROR')
        return $check;
        $amount = $res->amount;
        $name = $res->name;
        $account = $res->account;
        $ifsc = $res->ifsc;
        $mobile = $res->mobile;
        $wallet = $this->checkwallet($uid);
        
       /* $qtra = DB::table('qtra_acc')->where('account',$account);
        
        if($qtra->count() <= 0 || $qtra->first()->fetch_acc != 1)
        return response()->json(['status'=>'ERROR','message'=>'Please Verify Account First'],200);*/
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],200);
        $user = DB::table('users')->where('id',$uid)->first();
        $charge = $this->checkcharge($amount,'qtransfer',$user->package);
        if($charge != true)
        return response()->json(['status'=>'INFO','message'=>'Charges are not set for you please contact to admin!']);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction']);
        $maxlimit = $this->checkmaxlimit('qtransfer',$user->package);
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
            'account' => $account,
            'ifsc' => $ifsc,
            'mobile' => $mobile,
            'name' => $name,
            'otp' => $otp
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
        $check = $this->checkservice('qtransfer',$uid);
        if($check->status == 'ERROR')
        return $check;
        $amount = $txnData['amount'];
        $account = $txnData['account'];
        $ifsc = $txnData['ifsc'];
        $name = $txnData['name'];
        $mobile = $txnData['mobile'];
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!']);
        $user = DB::table('users')->where('id',$uid)->first();
        $charge = $this->checkcharge($amount,'qtransfer',$user->package);
        if($charge != true)
        return response()->json(['status'=>'ERROR','message'=>'Charges are not set for you please contact to admin!']);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction']);
        
        
        #Before transaction do entry
        $tempTxnid = 'QT'.rand(1000000000,9999999999);
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
                'mode' => 'NA',
                );
            $this->insertdata('qtransfertxn',$insdata);
            $this->debitEntry($uid,'Q-Transfer',$tempTxnid,$amount);
            $this->debitEntry($uid,'QT-Charge',$tempTxnid,$charge);
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
            $sendingData = array(
                "amount" => $amount,
                "beneficiary_name" => $name,
                "ref_key" => $tempTxnid,
                "mode" => "IMPS",
                "account_number" => $account,
                "ifsc" => $ifsc
                );
        // $url = "https://partners.ecuzen.in/api/qt/dotxn";
        $url = "https://partner.ecuzen.in/new/api/qtransfer/dotxn";
        $callbackUrl = env('APP_URL').'/api/quick-transfer-callback';
        $headers = array('api-key' => $api_key,'callback-url'=>$callbackUrl);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingData),$headers);
        // return $response;
        #for dummy response
        #previous response
        // $response = '{"txnid":"QT253457337","amount":10,"status":"SUCCESS","rrn":"315610721207","msg":"NA","mode":"IMPS","req":{"beneficiary_type":"bank_account","beneficiary_name":"Manas","unique_request_number":"QT253457337","payment_mode":"IMPS","amount":1,"account_number":"555710510003414","ifsc":"BKID0005557"}}';
        #current response
        /*$response = '{"status":"SUCCESS","msg":"NA","info":{"txnid":"QT3232635","amount":1,"charge":"4.72","account":"327512762984","ifsc":"ICIC0006771","name":"Ecuzen","status":"SUCCESS","rrn":"327512762984","msg":"NA","mode":"IMPS","user_refkey":"QT3144363706","callback_url":"https:\/\/aadharsee.com\/api\/quick-transfer-callback"}}';*/
        $response = json_decode($response);
        $status =  $response->status;
        $TxnDetails = $response->info??[];
        if($status=="SUCCESS" || $status=="PENDING" || $status=="IN_PROCESS" || $status=="ACCEPTED")
        {
            $successTypes = ['SUCCESS','success','Success','PENDING','pending','Pending'];
            if(in_array($TxnDetails->status,$successTypes))
            {
                $insdata = array(
                    'uid' => $uid,
                    'txnid' => $TxnDetails->txnid,
                    'account' => $TxnDetails->account,
                    'ifsc' => $TxnDetails->ifsc,
                    'bname' => $TxnDetails->name,
                    'amount' =>$TxnDetails->amount,
                    'status' => $TxnDetails->status,
                    'message' => $TxnDetails->msg,
                    'response' => json_encode($response),
                    'rrn' => $TxnDetails->rrn,
                    'mode' => $TxnDetails->mode,
                    );
                $this->updateData('qtransfertxn','txnid',$tempTxnid,$insdata);
                $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$TxnDetails->txnid]);
                $this->distributeCommission($uid,'QT-Commission','qtransfer',$amount,$TxnDetails->txnid);
                #check for existance account
                $check = DB::table('qtra_acc')->where(['mobile'=>$mobile,'account'=>$account])->count();
                if($check < 1)
                {
                    $accountdata = array(
                        'mobile' => $mobile,
                        'account' => $account,
                        'name' => $name,
                        'ifsc' => $ifsc,
                        'fetch_acc' => 0,
                        );
                    $this->insertdata('qtra_acc',$accountdata);
                }
                $data = array(
                    'date' => Carbon::now()->format('Y-m-d H:i:s'),
                    'name' => $TxnDetails->name,
                    'txnid' => $TxnDetails->txnid,
                    'account' => $TxnDetails->account,
                    'ifsc' => $TxnDetails->ifsc,
                    'status' => $TxnDetails->status,
                    'item1' => 'Quick Transfer',
                    'item2' => 'Q-Transfer charge',
                    'amount1' => $TxnDetails->amount,
                    'amount2' => $charge,
                    'total' => $TxnDetails->amount+$charge,
                    'support' => DB::table('settings')->where('name','cemail')->first()->value,
                    );
                return response()->json(['status'=>'SUCCESS','view'=>view('receipt.qtransfer',$data)->render(),'closing'=>$wallet - $amount+$charge],200);
            }
            else
            {
                $insdata = array(
                    'uid' => $uid,
                    'txnid' => $TxnDetails->txnid??$tempTxnid,
                    'account' => $account,
                    'ifsc' => $ifsc,
                    'bname' => $name,
                    'amount' =>$amount,
                    'status' => $status,
                    'message' => $TxnDetails->msg??'NA',
                    'response' => json_encode($response),
                    'rrn' => $TxnDetails->rrn??"NA",
                    'mode' => $TxnDetails->mode??"NA",
                );
                $this->updateData('qtransfertxn','txnid',$tempTxnid,$insdata);
                $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$TxnDetails->txnid]);
                $this->creditEntry($uid,'QT-REFUND',$TxnDetails->txnid,$amount+$charge);
                return response()->json(['status'=>'ERROR','message'=>$TxnDetails->msg]);
            }
        }
        else
        {
            $insdata = array(
                    'uid' => session('uid'),
                    'txnid' => $TxnDetails->txnid??$tempTxnid,
                    'account' => $account,
                    'ifsc' => $ifsc,
                    'bname' => $name,
                    'amount' =>$amount,
                    'status' => $status,
                    'message' => $TxnDetails->msg??'NA',
                    'response' => json_encode($response),
                    'rrn' => $TxnDetails->rrn??"NA",
                    'mode' => $TxnDetails->mode??"NA",
                );
                $this->updateData('qtransfertxn','txnid',$tempTxnid,$insdata);
                $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$TxnDetails->txnid??$tempTxnid]);
                $this->creditEntry($uid,'QT-REFUND',$TxnDetails->txnid??$tempTxnid,$amount+$charge);
                return response()->json(['status'=>'ERROR','message'=>$TxnDetails->msg??$response->msg]);
        }
    }
}
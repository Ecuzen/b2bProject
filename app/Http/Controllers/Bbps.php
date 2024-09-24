<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Bbps extends Controller
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
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $results = DB::table('bbpsincat')->get();
        // return $results;
        $data = ['title'=>$title,'logo'=>$logo,'results'=>$results];
        return view('bbps.index',$data);
    }
    public function opbyCat(Request $res)
    {
        $catKey = $res->key;
        $opData =  DB::table('bbpsnewop')->select('name','id')->where('catKey',$catKey)->get();
        $catName = DB::table('bbpsincat')->where('cat_key',$catKey)->first()->name;
        // return $opData;
        return response()->json(['status'=>'SUCCESS','view'=>view('bbps.operator',['data'=>$opData,'catName'=>$catName])->render()]);
    }
    public function getParams(Request $res)
    {
        $billerId = $res->billerId;
        $getDetails = DB::table('bbpsnewop')->where('id',$billerId)->first();
        // return $getDetails;
        return response()->json(['status'=>'SUCCESS','view'=>view('bbps.customParams',['data'=>$getDetails])->render()]);
    }
    public function fetchBill(Request $res)
    {
        
        $data = $res->all();
        $getDetails = DB::table('bbpsnewop')->where('id',$data['billerId'])->first();
        $rules = [
        'baseparam' => [],
        'param1' => [],
        'param2' => [],
        'param3' => [],
    ];
    $rules['baseparam'][] = 'required';
    $rules['baseparam'][] = 'regex:' . '/'.$getDetails->regex.'/';
    
    if ($getDetails->ad1_name != null) {
        $rules['param1'][] = 'required';
        $rules['param1'][] = 'regex:' . '/'.$getDetails->ad1_regex.'/';
    }
    if ($getDetails->ad2_name != null) {
        $rules['param2'][] = 'required';
        $rules['param2'][] = 'regex:' . '/'.$getDetails->ad2_regex.'/';
    }
    if ($getDetails->ad3_name != null) {
        $rules['param3'][] = 'required';
        $rules['param3'][] = 'regex:' . '/'.$getDetails->ad3_regex.'/';
    }
    $messages = [
        'baseparam.required' => $getDetails->displayname.' is required.',
        'baseparam.regex' => $getDetails->displayname.' does not match the required pattern.',
        'param1.required' => $getDetails->ad1_d_name.' is required.',
        'param1.regex' => $getDetails->ad1_d_name.' does not match the required pattern.',
        'param2.required' => $getDetails->ad2_d_name.' is required.',
        'param2.regex' => $getDetails->ad2_d_name.' does not match the required pattern.',
        'param3.required' => $getDetails->ad3_d_name.' is required.',
        'param3.regex' => $getDetails->ad3_d_name.' does not match the required pattern.',
    ];
    $validator = Validator::make($data, $rules,$messages);
    if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['status'=>'ERROR','data' => $errors], 200);
        }
    $uid = session('uid');
    $check = $this->checkservice('bbps',$uid);
    if($check->status == 'ERROR')
    return response()->json(['status'=>'INFO','message'=>$check->message]);
    $api_key = DB::table('settings')->where('name','api_key')->first()->value;
    $url = 'https://partner.ecuzen.in/new/api/bbps/fetchbill';
    $hash = $api_key.",".$data['baseparam'].",".$data['billerId'];
    $hash = hash('sha512',$hash);
    $sendingdata = [
        "api_key" => $api_key,
        "canumber" => $data['baseparam'],
        "opid" => $data['billerId'],
        $getDetails->ad1_name => $data['param1'],
        $getDetails->ad2_name => $data['param2'],
        $getDetails->ad3_name => $data['param3'],
        "hash" => $hash
        ];
      
        // return json_encode($sendingdata);
    $response = $this->callApi($url,'POST',json_encode($sendingdata),'p');
   /* return $response;*/
    $response = json_decode($response);
    if($response->status == 'ERROR')
    {
        return response()->json(['status'=>'INFO','message'=>$response->msg]);
    }
    $billdata = $response->billdata;
    session()->put('fetchData',$billdata);
    $fetchdata = array(
        'message' =>$response->msg,
        'name' => $billdata->name,
        'paramname' => $getDetails->displayname,
        'paramvalue' => $data['baseparam'],
        'item1' => 'Bill Date',
        'item1value' => $billdata->billdate,
        'item2' => 'Due Date',
        'item2value' => $billdata->duedate,
        'item3' => 'Amount',
        'item3value' => $billdata->amount,
        'support' => DB::table('settings')->where('name','cemail')->first()->value,
        );
    return response()->json(['status'=>'SUCCESS','view'=>view('receipt.bbpsFetch',$fetchdata)->render()]);
    
    }
    
    public function payBill(Request $res)
    {
        $billerId = $res->billerId;
        $getDetails = DB::table('bbpsnewop')->where('id',$billerId)->first();
        $regex = $getDetails->regex;
        $validator = Validator::make($res->all(), [
                'baseparam' => 'required|regex:'.'/'.$regex.'/',
                'amount' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errors = $errors->first();
                return response()->json(['status'=>'ERROR','message' => $errors], 200);
            }
        $amount = $res->amount;
        $baseparam = $res->baseparam;
        $uid = session('uid');
        $check = $this->checkservice('bbps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'INFO','message'=>$check->message]);
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],200);
        
        $temptxnid = 'BBPS'.rand(1000000000,9999999999);
        #Transaction entry
        $txnEntry = array(
            'uid' => session('uid'),
            'txnid' => $temptxnid,
            'status' => 'PENDING',
            'amount' => $amount,
            'response' => '',
            'canumber' => $baseparam,
            'boperator' => $billerId,
            );
        $this->insertdata('bbpstxn',$txnEntry);
        #debit section
        $this->debitEntry($uid,'BBPS',$temptxnid,$amount);
        #api call
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = 'https://partner.ecuzen.in/new/api/bbps/paybill';
        $hash = $api_key.",".$baseparam.",".$billerId.",".$amount;
        $hash = hash('sha512',$hash);
        $sendingdata = array(
            "api_key" => $api_key,
            "canumber" => $baseparam,
            "opid" => $billerId,
            "amount" => $amount,
            "hash" => $hash,
            "bill_fetch" => session('fetchData') != null ?session('fetchData'):'',
            );
        $response = $this->callApi($url,'POST',json_encode($sendingdata),'p');
        // $response = '{"status":"FAILED","msg":"The Bill Amount field must contain a number greater than 9.\n","txnid":"B16904388737960126805"}';
       /* $response = '{"status":"SUCCESS","msg":"You Bill Payment for Vodafone of Amount 10 is successful.","txnid":"B16904410007833200877"}';*/
        $response = json_decode($response);
        $status = isset($response->status)?$response->status:'NA';
        if($status == "SUCCESS")
        {  
            $txnid = $response->txnid;
            $txnEntry = array(
                'uid' => session('uid'),
                'txnid' => $txnid,
                'status' => $status,
                'response' => json_encode($response),
                );
            $this->updateData('bbpstxn','txnid',$temptxnid,$txnEntry);
            $this->updateData('wallet','txnid',$temptxnid,['txnid'=>$txnid]);
            #for giving commission
            $commissionAmount = 0;
            if($amount >= 100)
            {
                $getCommrow = DB::table('bbpscomm')->where('catkey',$getDetails->catkey)->first();
                if($getCommrow == '' || $getCommrow == null)
                return;
                if($getCommrow->byamount == 1)
                {
                    #serch result by desired amount
                    $getCommrow = DB::table('bbpscomm')->where('catkey',$getDetails->catkey)->where('froma','<=',$amount)->where('toa','>=',$amount)->first();
                    if($getCommrow != '' || $getCommrow != null)
                    {
                        if($getCommrow->percent == 1)
                        $commissionAmount = ($amount * $getCommrow->amount) /100;
                        else
                        $commissionAmount = $getCommrow->amount;
                    }
                }
                else
                {
                    if($getCommrow->percent == 1)
                    $commissionAmount = ($amount * $getCommrow->amount) /100;
                    else
                    $commissionAmount = $getCommrow->amount;
                }
                $this->creditEntry($uid,'BBPS-COMMISSION',$txnid,$commissionAmount);
            }
            $getCommrow = DB::table('bbpsincat')->where('cat_key',$getDetails->catkey)->first();
            $this->distributeComm($uid,'BBPS-COMMISSION','bbps',$amount,$txnid,$getCommrow->id);
            $data = array(
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => session('fetchData')->name,
                'txnid' => $txnid,
                'status' => $status,
                'item1' => 'BBPS',
                'item2' => 'BBPS-COMMISSION',
                'amount1' => $amount,
                'amount2' => $commissionAmount,
                'total' => $amount - $commissionAmount,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                );
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.bbps',$data)->render(),'closing'=>$wallet+$commissionAmount - $amount],200);
        }
        else
        {
            if(isset($response->txnid))
            {
                $txnid = $response->txnid;
                $txnEntry = array(
                    'uid' => session('uid'),
                    'txnid' => $txnid,
                    'status' => $status,
                    'response' => json_encode($response),
                    );
                $this->updateData('bbpstxn','txnid',$temptxnid,$txnEntry);
                $this->updateData('wallet','txnid',$temptxnid,['txnid'=>$txnid]);
                $this->creditEntry($uid,'BBPS-REFUND',$txnid,$amount);
            }
            else
            {
                $this->updateData('bbpstxn','txnid',$temptxnid,['response' => json_encode($response),'status'=>$status]);
                $this->creditEntry($uid,'BBPS-REFUND',$temptxnid,$amount);
            }
            return response()->json(['status'=>'ERROR','message'=>$response->msg]);
        }
    }
}

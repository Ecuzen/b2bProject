<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BbpsController extends Controller
{
    public function index()
    {
        $result = DB::table('bbps_categories')->orderBy('cat_key','asc')->get();
        return view('bbps-new.index',['results'=>$result]);
    }
    public function fetch_by_cat($cat_key)
    {
        $catBillers = DB::table('bbps_all_billers')->where('category_key',$cat_key)->orderBy('biller_name','asc');
        $catBillers = $catBillers->get();
        // return $catBillers;
        $categoryName = $catBillers->first()->category_name;
        return view('bbps-new.billers',['data'=>$catBillers,'catName' => $categoryName]);
    }
    public function get_params($billerId)
    {
        $getDetails = DB::table('bbps_all_billers')->where('id',$billerId)->first();
        if($getDetails->is_available != 1)
        return response()->json(['status' => 'ERROR','message' => 'Biller is inactive']);
        return response()->json(['status'=>'SUCCESS','view'=>view('bbps-new.customParams',['data'=>$getDetails])->render()]);
    }
    public function fetch_bill(Request $res)
    {
        try {
            $data = $res->all();
            $getDetails = DB::table('bbps_all_billers')->where('id',$data['billerId'])->first();
            $rules = [
                'param1' => [],
                'param2' => [],
                'param3' => [],
                'param4' => [],
            ];
            if ($getDetails->param1 != null) {
                if($getDetails->param1_mandatory ==1)
                {
                    $rules['param1'][] = 'required';
                    $rules['param1'][] = 'regex:' . '/'.$getDetails->param1_regex.'/';
                }
            }
            if ($getDetails->param2 != null) {
                if($getDetails->param2_mandatory ==1)
                {
                    $rules['param2'][] = 'required';
                    $rules['param2'][] = 'regex:' . '/'.$getDetails->param2_regex.'/';
                }
            }
            if ($getDetails->param3 != null) {
                if($getDetails->param3_mandatory ==1)
                {
                    $rules['param3'][] = 'required';
                    $rules['param3'][] = 'regex:' . '/'.$getDetails->param3_regex.'/';
                }
            }
            if ($getDetails->param4 != null) {
                if($getDetails->param4_mandatory ==1)
                {
                    $rules['param4'][] = 'required';
                    $rules['param4'][] = 'regex:' . '/'.$getDetails->param4_regex.'/';
                }
            }
            $messages = [
                'param1.required' => $getDetails->param1.' is required.',
                'param1.regex' => $getDetails->param1_regex.' does not match the required pattern.',
                'param2.required' => $getDetails->param2.' is required.',
                'param2.regex' => $getDetails->param2_regex.' does not match the required pattern.',
                'param3.required' => $getDetails->param3.' is required.',
                'param3.regex' => $getDetails->param3_regex.' does not match the required pattern.',
                'param4.required' => $getDetails->param4.' is required.',
                'param4.regex' => $getDetails->param4_regex.' does not match the required pattern.',
            ];
            // dd($rules);
            $validator = Validator::make($data, $rules,$messages);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','info' => $errors,'message' => $errors->first()], 200);
            }
            $uid = session('uid');
            $check = $this->checkservice('bbps',$uid);
            if($check->status == 'ERROR')
            return response()->json(['status'=>'INFO','message'=>$check->message]);
            $api_key = DB::table('settings')->where('name','api_key')->first()->value;
            $url = 'https://partner.ecuzen.in/new/api/bbps/v2/fetch_bill';
            $txnid = uniqid();
            $sendingData = array(
                "biller_id" => $getDetails->billerId,
                "param1" => $data['param1']??null,
                "param2" => $data['param2']??null,
                "param3" => $data['param3']??null,
                "param4" => $data['param4']??null,
                "txnid" => $txnid,
                'api_key' => DB::table('settings')->where('name','api_key')->first()->value
                );
                // return $sendingData;
            $response = $this->callApi($url,'POST',json_encode($sendingData),'p');
            // return $response;
            // $response = '{"status":"SUCCESS","msg":"Transaction Successful","data":{"enquiryReferenceId":"TSB43565df31eff4274972064de33381045","CustomerName":" RAMESHA PARIDA","BillNumber":"NA","BillPeriod":"NA","BillDate":"18\/11\/2023","BillDueDate":"28\/11\/2023","BillAmount":"332.00","CustomerParamsDetails":[{"Name":"Consumer Id","Value":"202S03695536"}],"BillDetails":[],"AdditionalDetails":[]},"skey":"a6544d14b989bd131d2ef3f564e59d33"}';
            $response = json_decode($response);
            if($response->status != 'SUCCESS')
            {
                return response()->json(['status'=>'INFO','message'=>$response->msg]);
            }
            $billData = $response->data;
            session()->put(['fetchDetails' => $billData]);
            $paramDetails = $billData->CustomerParamsDetails;
            $paramdetails = [];
            foreach ($paramDetails as $data) {
                $paramdetails[] = ['name' => $data->Name, 'value' => $data->Value];
            }
            $fetchdata = array(
                'reference' => $billData->enquiryReferenceId,
                'message' =>$response->msg,
                'name' => $billData->CustomerName??'NA',
                'billNumber' => $billData->BillNumber,
                'paramdetails' => $paramdetails,
                'item1' => 'Bill Date',
                'item1value' => $billData->BillDate,
                'item2' => 'Due Date',
                'item2value' => $billData->BillDueDate,
                'item3' => 'Amount',
                'item3value' => $billData->BillAmount,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                'skey' => $response->skey,
            );
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.bbps_bbps_fetch',$fetchdata)->render()]);
        }
        catch (\Exception $e)
        {
            return response()->json(['status' => 'ERROR' , 'message' => $e->getMessage()]);
        }
    }
    public function pay_bill(Request $res)
    {
        try {
            $data = $res->all();
            $getDetails = DB::table('bbps_all_billers')->where('id',$data['billerId'])->first();
            $rules = [
                'param1' => [],
                'param2' => [],
                'param3' => [],
                'param4' => [],
                'amount' => ['required','gt:0'],
                'skey' => [],
            ];
            if ($getDetails->param1 != null) {
                if($getDetails->param1_mandatory ==1)
                {
                    $rules['param1'][] = 'required';
                    $rules['param1'][] = 'regex:' . '/'.$getDetails->param1_regex.'/';
                }
            }
            if ($getDetails->param2 != null) {
                if($getDetails->param2_mandatory ==1)
                {
                    $rules['param2'][] = 'required';
                    $rules['param2'][] = 'regex:' . '/'.$getDetails->param2_regex.'/';
                }
            }
            if ($getDetails->param3 != null) {
                if($getDetails->param3_mandatory ==1)
                {
                    $rules['param3'][] = 'required';
                    $rules['param3'][] = 'regex:' . '/'.$getDetails->param3_regex.'/';
                }
            }
            if ($getDetails->param4 != null) {
                if($getDetails->param4_mandatory ==1)
                {
                    $rules['param4'][] = 'required';
                    $rules['param4'][] = 'regex:' . '/'.$getDetails->param4_regex.'/';
                }
            }
            $messages = [
                'param1.required' => $getDetails->param1.' is required.',
                'param1.regex' => $getDetails->param1_regex.' does not match the required pattern.',
                'param2.required' => $getDetails->param2.' is required.',
                'param2.regex' => $getDetails->param2_regex.' does not match the required pattern.',
                'param3.required' => $getDetails->param3.' is required.',
                'param3.regex' => $getDetails->param3_regex.' does not match the required pattern.',
                'param4.required' => $getDetails->param4.' is required.',
                'param4.regex' => $getDetails->param4_regex.' does not match the required pattern.',
            ];
            $validator = Validator::make($data, $rules,$messages);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','info' => $errors,'message' => $errors->first()], 200);
            }
            $amount = $res->amount;
            $uid = session('uid');
            $user = DB::table('users')->where('id',$uid)->first();
            $check = $this->checkservice('bbps',$uid);
            if($check->status == 'ERROR')
            return response()->json(['status'=>'INFO','message'=>$check->message]);
            $wallet = $this->checkwallet($uid);
            if($wallet < $amount)
            return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],200);
            $opid = DB::table('bbps_categories')->where('cat_key',$getDetails->category_key)->pluck('id')[0];
            $commission = DB::table('bbps')->where('package',$user->package)->where('opid',$opid)->first();
            $commissionAmount = 0;
            if($commission != null || $commission != "")
            {
                if($commission->percent == 1)
                    $commissionAmount = $amount * ($commission->amount / 100);
                else
                    $commissionAmount = $commission->amount;
            }
            $temptxnid = 'BBPS'.rand(1000000000,9999999999);
            #api call
            $api_key = DB::table('settings')->where('name','api_key')->first()->value;
            $url = 'https://partner.ecuzen.in/new/api/bbps/v2/pay_bill';
            $sendinDdata = array(
                "biller_id" => $getDetails->billerId,
                "param1" => $data['param1']??null,
                "param2" => $data['param2']??null,
                "param3" => $data['param3']??null,
                "param4" => $data['param4']??null,
                "txnid" => $temptxnid,
                "api_key" => $api_key,
                "skey" => $data['skey']??null,
                "amount" => $data['amount']
                );
            #Transaction entry
            $txnEntry = array(
                'uid' => $uid,
                'txnid' => $temptxnid,
                'status' => 'PENDING',
                'amount' => $amount,
                'response' => '',
                'canumber' => $data['param1']??null,
                'boperator' => $getDetails->id,
                "req" => json_encode($sendinDdata),
                );
            $this->insertdata('bbpstxn',$txnEntry);
            #debit section
            $this->debitEntry($uid,'BBPS',$temptxnid,$amount);
            $response = $this->callApi($url,'POST',json_encode($sendinDdata),'p');
            #failure response 
           /* $response = '{"status":"FAILED","msg":"Calculation configuration issue","txnid":"BBPS4287709606","data":{"uid":73,"bid":546,"txnid":"BBPS4287709606","amount":1,"commission":"1","status":"FAILED","date":"2023-12-04 08:06:41","id":1862,"response":"{\"statuscode\":\"ISE\",\"actcode\":null,\"status\":\"Calculation configuration issue\",\"data\":null,\"timestamp\":\"2023-12-04 13:36:42\",\"ipay_uuid\":\"h0069ac44283-8a14-4b80-89f4-6d38671ca735-ELcPfBN0pEuh\",\"orderid\":null,\"environment\":\"LIVE\",\"internalCode\":null}","msg":"Calculation configuration issue"}}';*/
            #success response
            /*$response = '{"status":"SUCCESS","msg":"Transaction Successful","txnid":"BBPS4592918907","data":{"uid":73,"bid":546,"txnid":"BBPS4592918907","amount":"332.00","commission":"1","status":"SUCCESS","date":"2023-12-04 10:09:07","id":1865,"response":"{\"statuscode\":\"TXN\",\"actcode\":null,\"status\":\"Transaction Successful\",\"data\":{\"externalRef\":\"BBPS4592918907\",\"poolReferenceId\":\"1231204153908FKJPU\",\"txnValue\":\"332.00\",\"txnReferenceId\":\"TJ01333803390895B72A\",\"pool\":{\"account\":\"8094947450\",\"openingBal\":\"1515.55\",\"mode\":\"DR\",\"amount\":\"330.70\",\"closingBal\":\"1184.85\"},\"billerDetails\":{\"name\":\"TP Central Odisha Distribution Ltd.\",\"account\":\"202S03695536\"},\"billDetails\":{\"CustomerName\":\" RAMESHA PARIDA\",\"BillNumber\":\"202311182312202S03695536\",\"BillPeriod\":\"1\",\"BillDate\":\"18\\\/11\\\/2023\",\"BillDueDate\":\"28\\\/11\\\/2023\",\"BillAmount\":\"332.00\",\"CustomerParamsDetails\":[{\"Name\":\"Consumer Id\",\"Value\":\"202S03695536\"}],\"AdditionalDetails\":null}},\"timestamp\":\"2023-12-04 15:39:10\",\"ipay_uuid\":\"h0689ac46e4c-0862-4388-8812-60a96dac1bcf-mlrQzxBnbvtH\",\"orderid\":\"1231204153908FKJPU\",\"environment\":\"LIVE\"}","msg":"Transaction Successful"}}';*/
            $response = json_decode($response);
            $fetchData = session()->get('fetchDetails') ?? [];
            $paramdetails = [];
            foreach ($fetchData->CustomerParamsDetails as $data) {
                $paramdetails[] = ['name' => $data->Name, 'value' => $data->Value];
            }
            if($response->status == 'SUCCESS')
            {
                # data to be updated 
                $updateData = array(
                    'status' => $response->status,
                    'response' => json_encode($response),
                    );
                DB::table('bbpstxn')->where('txnid', $temptxnid)->update($updateData);
                $this->creditEntry($uid,'BBPS-COMMISSION',$temptxnid,$commissionAmount);
                $this->distributeComm($uid,'BBPS-COMMISSION','bbps',$amount,$temptxnid,$opid);
                $data = array(
                    'date' => Carbon::now()->format('Y-m-d H:i:s'),
                    'name' => $fetchData->CustomerName??'NA',
                    'paramdetails' => $paramdetails,
                    'txnid' => $response->txnid,
                    'status' => $response->status,
                    'message' => $response->msg,
                    'item1' => 'BBPS',
                    'item2' => 'BBPS-COMMISSION',
                    'amount1' => $amount,
                    'amount2' => $commissionAmount,
                    'total' => $amount - $commissionAmount,
                    'support' => DB::table('settings')->where('name','cemail')->first()->value,
                );
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.bbps_new',$data)->render(),'closing'=>$wallet+$commissionAmount - $amount],200);
            }
            else
            {
                $updateData = array(
                    'status' => $response->status,
                    'response' => json_encode($response),
                    );
                DB::table('bbpstxn')->where('txnid', $temptxnid)->update($updateData);
                $this->creditEntry($uid,'BBPS-REFUND',$temptxnid,$amount);
                return response()->json(['status' => $response->status,'message' => $response->msg]);
            }
        }
        catch (\Exception $e)
        {
            return response()->json(['status' => 'ERROR' , 'message' => $e->getMessage()]);
        }
    }
    public function subscription(Request $req,$type)
    {
        $category_key = $type == 'fastag' ? 'C10' : 'C15';
        $billers = DB::table('bbps_all_billers')->where('category_key',$category_key)->orderBy('biller_name','asc')->select('id','billerId','biller_name as biller','icon_url as icon')->where('is_available',1)->get();
        return $req->req == 'api' ? response()->json(['status' => 'SUCCESS' ,'message' => 'Billers Fetched successfully!!','type' => $type,'data' => ['subscription_billers' => $billers]]) : view('bbps-new.subscription',['billers'=>$billers,'catHead' => $type == 'fastag' ? 'FASTag' : 'Credit Card','type'=>$type]);
    }
    public function get_details(Request $req,$type,$id)
    {
        $getDetails = DB::table('bbps_all_billers')->where('id',$id)->first();
        if($getDetails->is_available != 1)
        return response()->json(['status' => 'ERROR','message' => 'Biller is inactive'],$req->req == 'api' ? 405 : 200);
        return $req->req == 'api' ? response()->json(['status' => 'SUCCESS' ,'message' => 'Biller Details successfully!!','type' => $type,'data' => ['biller_details' => $getDetails]]) : view('bbps-new.billerScreen',['biller' => $getDetails,'catHead' => $type == 'fastag' ? 'FASTag' : 'Credit Card']);
    }
    public function subscription_fetch_bill(Request $res)
    {
        try {
            $data = $res->all();
            if(!isset($data['billerId']))
            return response()->json(['status' => 'ERROR' , 'message' => 'Send valid data'],$res->req == 'api' ? 400 : 200);
            $getDetails = DB::table('bbps_all_billers')->where('id',$data['billerId'])->first();
            $rules = [
                'param1' => [],
                'param2' => [],
                'param3' => [],
                'param4' => [],
            ];
            if ($getDetails->param1 != null) {
                if($getDetails->param1_mandatory ==1)
                {
                    $rules['param1'][] = 'required';
                    $rules['param1'][] = 'regex:' . '/'.$getDetails->param1_regex.'/';
                }
            }
            if ($getDetails->param2 != null) {
                if($getDetails->param2_mandatory ==1)
                {
                    $rules['param2'][] = 'required';
                    $rules['param2'][] = 'regex:' . '/'.$getDetails->param2_regex.'/';
                }
            }
            if ($getDetails->param3 != null) {
                if($getDetails->param3_mandatory ==1)
                {
                    $rules['param3'][] = 'required';
                    $rules['param3'][] = 'regex:' . '/'.$getDetails->param3_regex.'/';
                }
            }
            if ($getDetails->param4 != null) {
                if($getDetails->param4_mandatory ==1)
                {
                    $rules['param4'][] = 'required';
                    $rules['param4'][] = 'regex:' . '/'.$getDetails->param4_regex.'/';
                }
            }
            $messages = [
                'param1.required' => $getDetails->param1.' is required.',
                'param1.regex' => $getDetails->param1_regex.' does not match the required pattern.',
                'param2.required' => $getDetails->param2.' is required.',
                'param2.regex' => $getDetails->param2_regex.' does not match the required pattern.',
                'param3.required' => $getDetails->param3.' is required.',
                'param3.regex' => $getDetails->param3_regex.' does not match the required pattern.',
                'param4.required' => $getDetails->param4.' is required.',
                'param4.regex' => $getDetails->param4_regex.' does not match the required pattern.',
            ];
            // dd($rules);
            $validator = Validator::make($data, $rules,$messages);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','info' => $errors,'message' => $errors->first()], $res->req == 'api' ? 400 : 200);
            }
            $user = $res->user();
            $uid = $user->id;
            $check = $this->checkservice('bbps',$uid);
            if($check->status == 'ERROR')
            return response()->json(['status'=>'INFO','message'=>$check->message]);
            $api_key = DB::table('settings')->where('name','api_key')->first()->value;
            if($getDetails->category_key == 'C10')
            $url = 'https://partner.ecuzen.in/new/api/fast_tag/fetch_bill';
            else
            $url = 'https://partner.ecuzen.in/new/api/credit_card/fetch_bill';
            $txnid = uniqid();
            $sendingData = array(
                "biller_id" => $getDetails->billerId,
                "param1" => $data['param1']??null,
                "param2" => $data['param2']??null,
                "param3" => $data['param3']??null,
                "param4" => $data['param4']??null,
                "txnid" => $txnid,
                'api_key' => DB::table('settings')->where('name','api_key')->first()->value
                );
                // return $sendingData;
            $response = $this->callApi($url,'POST',json_encode($sendingData),'p');
            // DB::table('test')->insert(['data' => json_encode(['url' => $url,'body' => $sendingData,'response' => $response])]);
            // return $response;
            // $response = '{"status":"SUCCESS","msg":"Transaction Successful","data":{"enquiryReferenceId":"TSB43565df31eff4274972064de33381045","CustomerName":" RAMESHA PARIDA","BillNumber":"NA","BillPeriod":"NA","BillDate":"18\/11\/2023","BillDueDate":"28\/11\/2023","BillAmount":"332.00","CustomerParamsDetails":[{"Name":"Consumer Id","Value":"202S03695536"}],"BillDetails":[],"AdditionalDetails":[]},"skey":"a6544d14b989bd131d2ef3f564e59d33"}';
            $response = json_decode($response);
            if($response->status != 'SUCCESS')
            {
                return response()->json(['status'=>'INFO','message'=>$response->msg],$res->req == 'api' ? 422 : 200);
            }
            $billData = $response->data;
            DB::table('bbps_session_data')->insert(['skey' => $response->skey, 'data' => json_encode($billData)]);
            $paramDetails = $billData->CustomerParamsDetails;
            $paramdetails = [];
            foreach ($paramDetails as $data) {
                $paramdetails[] = ['name' => $data->Name, 'value' => $data->Value];
            }
            $fetchdata = array(
                'reference' => $billData->enquiryReferenceId,
                'message' =>$response->msg,
                'name' => $billData->CustomerName??'NA',
                'billNumber' => $billData->BillNumber,
                'paramdetails' => $paramdetails,
                'item1' => 'Bill Date',
                'item1value' => $billData->BillDate,
                'item2' => 'Due Date',
                'item2value' => $billData->BillDueDate,
                'item3' => 'Amount',
                'item3value' => $billData->BillAmount,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                'skey' => $response->skey,
            );
            return $res->req == 'api' ? response()->json(['status' => 'SUCCESS' ,'message' => 'Bill details fetched successfully' ,'data' => ['subscription_fetch_bill' => $fetchdata]]) :
            response()->json(['status'=>'SUCCESS','view'=>view('receipt.bbps_bbps_fetch',$fetchdata)->render()]);
        }
        catch (\Exception $e)
        {
            return response()->json(['status' => 'ERROR' , 'message' => $e->getMessage()],$res->req == 'api' ? 500 : 200);
        }
    }
    public function subscription_pay_bill(Request $res)
    {
        try {
            $data = $res->all();
            if(!isset($data['billerId']))
            return response()->json(['status' => 'ERROR' , 'message' => 'Send valid data'],$res->req == 'api' ? 400 : 200);
            $getDetails = DB::table('bbps_all_billers')->where('id',$data['billerId'])->first();
            $rules = [
                'param1' => [],
                'param2' => [],
                'param3' => [],
                'param4' => [],
                'amount' => ['required','gt:0'],
                'skey' => [],
            ];
            if ($getDetails->param1 != null) {
                if($getDetails->param1_mandatory ==1)
                {
                    $rules['param1'][] = 'required';
                    $rules['param1'][] = 'regex:' . '/'.$getDetails->param1_regex.'/';
                }
            }
            if ($getDetails->param2 != null) {
                if($getDetails->param2_mandatory ==1)
                {
                    $rules['param2'][] = 'required';
                    $rules['param2'][] = 'regex:' . '/'.$getDetails->param2_regex.'/';
                }
            }
            if ($getDetails->param3 != null) {
                if($getDetails->param3_mandatory ==1)
                {
                    $rules['param3'][] = 'required';
                    $rules['param3'][] = 'regex:' . '/'.$getDetails->param3_regex.'/';
                }
            }
            if ($getDetails->param4 != null) {
                if($getDetails->param4_mandatory ==1)
                {
                    $rules['param4'][] = 'required';
                    $rules['param4'][] = 'regex:' . '/'.$getDetails->param4_regex.'/';
                }
            }
            $messages = [
                'param1.required' => $getDetails->param1.' is required.',
                'param1.regex' => $getDetails->param1_regex.' does not match the required pattern.',
                'param2.required' => $getDetails->param2.' is required.',
                'param2.regex' => $getDetails->param2_regex.' does not match the required pattern.',
                'param3.required' => $getDetails->param3.' is required.',
                'param3.regex' => $getDetails->param3_regex.' does not match the required pattern.',
                'param4.required' => $getDetails->param4.' is required.',
                'param4.regex' => $getDetails->param4_regex.' does not match the required pattern.',
            ];
            $validator = Validator::make($data, $rules,$messages);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','info' => $errors,'message' => $errors->first()], $res->req == 'api' ? 400 : 200);
            }
            $amount = $res->amount;
            $user = $res->user();
            $uid = $user->id;
            $check = $this->checkservice('bbps',$uid);
            if($check->status == 'ERROR')
            return response()->json(['status'=>'ERROR','message'=>$check->message]);
            $wallet = $this->checkwallet($uid);
            if($wallet < $amount)
            return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],$res->req == 'api' ? 402 : 200);
            // $opid = DB::table('bbps_categories')->where('cat_key',$getDetails->category_key)->pluck('id')[0];
            // $commission = DB::table('bbps')->where('package',$user->package)->where('opid',$opid)->first();
            $commissionAmount = 0;
            // if($commission != null || $commission != "")
            // {
            //     if($commission->percent == 1)
            //         $commissionAmount = $amount * ($commission->amount / 100);
            //     else
            //         $commissionAmount = $commission->amount;
            // }
            $temptxnid = 'BBPS'.rand(1000000000,9999999999);
            #api call
            $api_key = DB::table('settings')->where('name','api_key')->first()->value;
            if($getDetails->category_key == 'C10')
            $url = 'https://partner.ecuzen.in/new/api/fast_tag/pay_bill';
            else
            $url = 'https://partner.ecuzen.in/new/api/credit_card/pay_bill';
            $sendinDdata = array(
                "biller_id" => $getDetails->billerId,
                "param1" => $data['param1']??null,
                "param2" => $data['param2']??null,
                "param3" => $data['param3']??null,
                "param4" => $data['param4']??null,
                "txnid" => $temptxnid,
                "api_key" => $api_key,
                "skey" => $data['skey']??null,
                "amount" => $data['amount']
                );
            #Transaction entry
            $txnEntry = array(
                'uid' => $uid,
                'txnid' => $temptxnid,
                'status' => 'PENDING',
                'amount' => $amount,
                'response' => '',
                'canumber' => $data['param1']??null,
                'boperator' => $getDetails->id,
                'type' => $getDetails->category_key == 'C10' ? 'fastag' : 'credit',
                "req" => json_encode($sendinDdata),
                );
            $this->insertdata('bbpstxn',$txnEntry);
            #debit section
            $type = $getDetails->category_key == 'C10' ? 'FASTAG' : 'CC';
            $this->debitEntry($uid,$type,$temptxnid,$amount);
            $response = $this->callApi($url,'POST',json_encode($sendinDdata),'p');
            #failure response 
            /*$response = '{"status":"FAILED","msg":"Calculation configuration issue","txnid":"BBPS4287709606","data":{"uid":73,"bid":546,"txnid":"BBPS4287709606","amount":1,"commission":"1","status":"FAILED","date":"2023-12-04 08:06:41","id":1862,"response":"{\"statuscode\":\"ISE\",\"actcode\":null,\"status\":\"Calculation configuration issue\",\"data\":null,\"timestamp\":\"2023-12-04 13:36:42\",\"ipay_uuid\":\"h0069ac44283-8a14-4b80-89f4-6d38671ca735-ELcPfBN0pEuh\",\"orderid\":null,\"environment\":\"LIVE\",\"internalCode\":null}","msg":"Calculation configuration issue"}}';*/
            #success response
           /* $response = '{"status":"SUCCESS","msg":"Transaction Successful","txnid":"BBPS4592918907","data":{"uid":73,"bid":546,"txnid":"BBPS4592918907","amount":"332.00","commission":"1","status":"SUCCESS","date":"2023-12-04 10:09:07","id":1865,"response":"{\"statuscode\":\"TXN\",\"actcode\":null,\"status\":\"Transaction Successful\",\"data\":{\"externalRef\":\"BBPS4592918907\",\"poolReferenceId\":\"1231204153908FKJPU\",\"txnValue\":\"332.00\",\"txnReferenceId\":\"TJ01333803390895B72A\",\"pool\":{\"account\":\"8094947450\",\"openingBal\":\"1515.55\",\"mode\":\"DR\",\"amount\":\"330.70\",\"closingBal\":\"1184.85\"},\"billerDetails\":{\"name\":\"TP Central Odisha Distribution Ltd.\",\"account\":\"202S03695536\"},\"billDetails\":{\"CustomerName\":\" RAMESHA PARIDA\",\"BillNumber\":\"202311182312202S03695536\",\"BillPeriod\":\"1\",\"BillDate\":\"18\\\/11\\\/2023\",\"BillDueDate\":\"28\\\/11\\\/2023\",\"BillAmount\":\"332.00\",\"CustomerParamsDetails\":[{\"Name\":\"Consumer Id\",\"Value\":\"202S03695536\"}],\"AdditionalDetails\":null}},\"timestamp\":\"2023-12-04 15:39:10\",\"ipay_uuid\":\"h0689ac46e4c-0862-4388-8812-60a96dac1bcf-mlrQzxBnbvtH\",\"orderid\":\"1231204153908FKJPU\",\"environment\":\"LIVE\"}","msg":"Transaction Successful"}}';*/
            $response = json_decode($response);
            $paramdetails = [];
            if($getDetails->fetch_bill != 0)
            {
                $fetchData = DB::table('bbps_session_data')->where('skey',$data['skey']??null)->first();
                if($fetchData != null)
                {
                    $fetchData = json_decode($fetchData->data);
                    foreach ($fetchData->CustomerParamsDetails as $data) {
                        $paramdetails[] = ['name' => $data->Name, 'value' => $data->Value];
                    }
                }
            }
            if($response->status == 'SUCCESS')
            {
                # data to be updated 
                $updateData = array(
                    'status' => $response->status,
                    'response' => json_encode($response),
                    );
                DB::table('bbpstxn')->where('txnid', $temptxnid)->update($updateData);
                $this->creditEntry($uid,$type.'-COMMISSION',$temptxnid,$commissionAmount);
                // $this->distributeComm($uid,$type.'-COMMISSION','bbps',$amount,$temptxnid,$opid);
                $data = array(
                    'date' => Carbon::now()->format('Y-m-d H:i:s'),
                    'name' => $fetchData->CustomerName??'NA',
                    'paramdetails' => $paramdetails,
                    'txnid' => $response->txnid,
                    'status' => $response->status,
                    'message' => $response->msg,
                    'item1' => $type,
                    'item2' => $type.'-COMMISSION',
                    'amount1' => $amount,
                    'amount2' => $commissionAmount,
                    'total' => $amount - $commissionAmount,
                    'support' => DB::table('settings')->where('name','cemail')->first()->value,
                );
                return $res->req == 'api' ? response()->json(['status' => 'SUCCESS' ,'message' => 'Subscription Receipt data' ,'data' => ['subscription_pay_bill' => $data]]) :
            response()->json(['status'=>'SUCCESS','view'=>view('receipt.bbps_new',$data)->render(),'closing'=>$wallet+$commissionAmount - $amount],200);
            }
            else
            {
                $updateData = array(
                    'status' => $response->status,
                    'response' => json_encode($response),
                    );
                DB::table('bbpstxn')->where('txnid', $temptxnid)->update($updateData);
                $this->creditEntry($uid,$type.'-REFUND',$temptxnid,$amount);
                return response()->json(['status' => $response->status,'message' => $response->msg],$res->req == 'api' ? 422 : 200);
            }
        }
        catch (\Exception $e)
        {
            return response()->json(['status' => 'ERROR' , 'message' => $e->getMessage()],$res->req == 'api' ? 500 : 200);
        }
    }
}

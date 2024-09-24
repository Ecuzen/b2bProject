<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AepsController extends Controller
{
    public function __construct()
    {
        if(session('uid') == null)
        {
            return redirect()->route('user.login');
        }
        #check for aeps kyc 
        $checkKyc = DB::table('aepskyc')->where('uid', session('uid'))->count();
        if($checkKyc != 1)
        {
            return index();
        }
    }
    
    
    public function index()
    {
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $data = ['title' => $title,'logo' => $logo];
        $uid = session('uid');
        $userData = DB::table('users')->where('id',$uid)->first();
        #check for kyc 
        $checkKyc = DB::table('aepskyc')->where('uid', $uid)->count();
        if($checkKyc != 1)
        {
            $states = DB::table('icicstate')->get();
            $details = DB::table('users as u')->join('kyc','kyc.uid' ,'=','u.id')->select('kyc.*','u.name','u.phone','u.email')->where('u.id',session('uid'))->get();
            $data['states'] = $states;
            $details = json_decode($details,true);
            $data['details'] = $details[0];
            return view('aeps.kyc',$data);
        }
        $getRow = DB::table('aepskyc')->where('uid', $uid)->first();
        if($getRow->status == 1 )
        {
            $data['outlet'] = $getRow->outlet;
            $data['phone'] = $userData->phone;
            return view('aeps.otp',$data);
        }
        else if($getRow->status == 2)
        {  
            if(Session::has('otpTimeout') && session('otpTimeout')->isFuture())
            {
                $getKyc = DB::table('kyc')->where('uid',$uid)->first();
                $aadhar = "XXXXXXXX".substr($getKyc->adhaar,-4);
                $data['aadhar'] = $aadhar;
                return view('aeps.finger',$data);
            }
            else
            {
                $data['outlet'] = $getRow->outlet;
                $data['phone'] = $userData->phone;
                return view('aeps.otp',$data);
            }
        }
        else
        {
            $tfaDate = Carbon::parse($getRow->tfadate)->toDateString();
            $current = date('Y-m-d', strtotime(Carbon::now()));
            if($current != $tfaDate)
            {
                $getKyc = DB::table('kyc')->where('uid',$uid)->first();
                $aadhar = "XXXXXXXX".substr($getKyc->adhaar,-4);
                $data['aadhar'] = $aadhar;
                return view('aeps.tfaVerify',$data);
            }
            #check for Aadhar pay TFA
            $aptfaDate = Carbon::parse($getRow->aptfa)->toDateString();
            if($current != $aptfaDate)
            $aptfa = false;
            else
            $aptfa = true;
            $data['aptfa'] = $aptfa;
            $data['banks'] = DB::table('banks')->get();
            return view('aeps.index',$data);
        }
    }
    public function loadApTfa()
    {
        $uid = session('uid');
        $getRow = DB::table('aepskyc')->where('uid', $uid)->first();
        $current = date('Y-m-d', strtotime(Carbon::now()));
        $aptfaDate = Carbon::parse($getRow->aptfa)->toDateString();
        if($current == $aptfaDate)
        return redirect()->to('/aeps');
        $getKyc = DB::table('kyc')->where('uid',$uid)->first();
        $aadhar = "XXXXXXXX".substr($getKyc->adhaar,-4);
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $data = ['title' => $title,'logo' => $logo,'aadhar'=>$aadhar];
        return view('aeps.aptfa',$data);
    }
    public function dokyc(Request $res)
    {
       $validator = Validator::make($res->all(), [
                'name' => 'required|string',
                'aadhar' => 'required|numeric:digits:12',
                'email' => 'required|email',
                'pincode' => 'required|numeric|digits:6',
                'address' => 'required|string',
                'phone' => 'required|numeric|digits:10',
                'shopname' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|numeric|not_in:0',
                'lat' => 'required',
                'log' => 'required',
            ]);
            $validator->setCustomMessages([
            'state.not_in' => 'Please select a state.',
            'shopname.required' => 'Please enter your complete shop name',
            'city.required' => 'Please enter your complete city name',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['status'=>'ERROR','data' => $errors], 200);
        }
        $uid = session('uid');
        $aadhar = $res->aadhar;
        $name = $res->name;
        $email = $res->email;
        $pincode = $res->pincode;
        $address = $res->address;
        $pan = $res->pan;
        $phone = $res->phone;
        $shopname = $res->shopname;
        $city = $res->city;
        $state = $res->state;
        #check for already registered or not
        $checkExistance = DB::table('aepskyc')->where('uid',$uid)->count();
        if($checkExistance > 0)
        return response()->json(['status'=>'INFO','message'=>'Already completed the kyc please contact to admin for further process!!']);
        #fetch kyc data
        $kycData = DB::table('kyc')->where('uid',$uid)->first();
        #check if the current data and existing data are different
        if($aadhar != $kycData->adhaar || $pan != $kycData->pan)
        {
            #update the existing data with new data
            $upData = array(
                'adhaar' => $aadhar,
                'pan' => $pan
                );
            DB::table('kyc')->where('uid',$uid)->update($upData);
        }
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partner.ecuzen.in/new/api/aeps/onboard";
        $sendingdata = array(
                "api_key" => $api_key,
                "latitude" => $res->lat,
                "longitude" => $res->log,
                "name" => $name,
                "mobile" => $phone,
                "email" => $email,
                "shopname" => $shopname,
                "city" => $city,
                "state" => $state,
                "pincode" => $pincode,
                "district" => $city,
                "address" => $address,
                "panno" => $pan,
                "aadharno" => $aadhar
            );
        $response = $this->callApi($url,'POST',$sendingdata,'');
        // $response = '{"status":"SUCCESS","msg":"KYC COMPLETED","response":"successful","outlet_id":"ECZ7508537784"}';
       
        $response = json_decode($response);
        
        if($response->status == 'SUCCESS')
        {
            if(isset($response->validated) && $response->validated == '1')
            {
                $sta = 3;
            }else{
                $sta = 1;
            }
            $insertData = array(
                'uid' => $uid,
                'outlet' => $response->outlet_id,
                'status' => $sta,
                'response' => json_encode($response),
                'tfadate' => Carbon::now()->subDay(),
                );
            DB::table('aepskyc')->insert($insertData);
            return response()->json(['status'=>'SUCCESS','message'=>'Your outlet ID has been generated go for further procedures!!']);
        }
        else
        {
            return response()->json(['status'=>'INFO','message'=>$response->msg]);
        }
    }
    public function kycSendotp(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'lat' => 'required',
                'log' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','message' => $errors->first(),'info' => $errors], 200);
            }
        $uid = session('uid');
        $outlet = $res->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = 'https://partner.ecuzen.in/new/api/aeps/send_otp';
        $sendingdata = array(
                "api_key" => $api_key,
                "latitude" => $res->lat,
                "longitude" => $res->log,
                "outlet" =>$outlet
            );
        $response = $this->callApi($url,'POST',$sendingdata,'');
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $upData = array(
                'response' => json_encode($response),
                );
            DB::table('aepskyc')->where('uid',$uid)->update($upData);
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'view'=>view('aeps.verifyotp',['id'=>1])->render()]);
        }
        if($response->status == 'ERROR')
        {
            DB::table('aepskyc')->where('uid', $uid)->delete();
            if(isset($response->response))
            return response()->json(['status'=>'ERROR','message'=>$response->response]);
            else
            return response()->json(['status'=>'ERROR','message'=>$response->msg]);
        }
    }
    
    public function verifyotp(Request $res)
    {
        $uid = session('uid');
        $outlet = $res->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = 'https://partner.ecuzen.in/new/api/aeps/verify_otp';
        $otp = $res->otp;
        $sendingdata = array(
            'api_key' => $api_key,
            'otp' => $otp,
            'outlet' => $outlet
            );
        $response = $this->callApi($url,'POST',$sendingdata,'');
        // $response = '{"status":"SUCCESS","msg":"OTP VERIFIED","response":"Request Completed"}';
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            session()->put('otpTimeout',Carbon::now()->addMinutes(2));
            $upData = array(
                'status' => 2,
                'response' => json_encode($response)
                );
            DB::table('aepskyc')->where('uid',$uid)->update($upData);
            return response()->json(['status'=>'SUCCESS','message'=>'OTP Verified successfully go for further procedure']);
        }
        else
        {
            $upData = array(
                'status' => 1,
                'response' => json_encode($response)
                );
            DB::table('aepskyc')->where('uid',$uid)->update($upData);
            return response()->json(['status'=>'ERROR','message'=>$response->response]);
        }
    }
    
    public function verifyfinger(Request $res)
    {
        $uid = session('uid');
        $outlet = DB::table('aepskyc')->where('uid',$uid)->first()->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = 'https://partner.ecuzen.in/new/api/aeps/biometric_verification';
        $sendingdata = array(
            'api_key' => $api_key,
            'outlet' => $outlet,
            'dsrno' => $res->dsrno,
            'pidtype' => $res->pidType,
            'piddata' => $res->pidData,
            'ci' => $res->ci,
            'dc' => $res->dc,
            'dpid' => $res->dpId,
            'errorcode' => $res->errorCode,
            'fcount' => $res->fCount,
            'ftype' => $res->fType,
            'hmac' => $res->hmac,
            'mc' => $res->mc,
            'mi' => $res->mi,
            'nmpoints' => $res->nmPoints,
            'qscore' => $res->qScore,
            'rdsid' => $res->rdsId,
            'rdsver' => $res->rdsVer,
            'sessionkey' => $res->sessionKey,
            'errorinfo' => $res->errorInfo
            );
        $response = $this->callApi($url,'POST',$sendingdata,'');
        // return $response;
        // $response = '{"status":"ERROR","msg":"ERROR IN KYC","response":"Invalid Details in BioMetric API"}';
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $upData = array(
                'status' => 3,
                'response' => json_encode($response),
                'aptfa' => date('Y-m-d H:i:s', strtotime(Carbon::now()->startOfDay()->subDay()))
                );
            $this->updateData('aepskyc','uid',$uid,$upData);
        return response()->json(['status'=>'SUCCESS','type'=>'kyc','message'=>$response->response]);
        }
        else
        {
            $upData = array(
                'status' => 1,
                'response' => json_encode($response)
                );
            $this->updateData('aepskyc','uid',$uid,$upData);
            return response()->json(['status'=>'ERROR','message'=>$response->response]);
        }
    }
    
    public function tfaVerification(Request $res)
    {
        $uid = session('uid');
         $wallet = DB::table('users')->where('id',$uid)->first()->wallet;
        if($wallet < 1){
           
             return response()->json(['status'=>'ERROR','message'=>'Minimum 1 rupee has been in your wallet to do 2fa']);
        }
        $outlet = DB::table('aepskyc')->where('uid',$uid)->first()->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = 'https://partner.ecuzen.in/new/api/aeps/tfa';
        $sendingdata = array(
            'api_key' => $api_key,
            'outlet' => $outlet,
            'dsrno' => $res->dsrno,
            'pidtype' => $res->pidType,
            'piddata' => $res->pidData,
            'ci' => $res->ci,
            'dc' => $res->dc,
            'dpid' => $res->dpId,
            'errorcode' => $res->errorCode,
            'fcount' => $res->fCount,
            'ftype' => $res->fType,
            'hmac' => $res->hmac,
            'mc' => $res->mc,
            'mi' => $res->mi,
            'nmpoints' => $res->nmPoints,
            'qscore' => $res->qScore,
            'rdsid' => $res->rdsId,
            'rdsver' => $res->rdsVer,
            'sessionkey' => $res->sessionKey,
            'errorinfo' => $res->errorInfo
            );
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),['api-key' => $api_key]);
        // DB::table('test')->insert(['data'=>$response]);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $upData = array(
                'tfadate' => date('Y-m-d H:i:s', strtotime(Carbon::now())),
                'tfaresponse' => json_encode($response)
                );
            $this->updateData('aepskyc','uid',$uid,$upData);
            if(isset($response->charge)){
                $charge = $response->charge;
                if($charge != 0){
                    $tempTxnid = 'TFA'.rand(1000000000,9999999999);
                    $this->debitEntry($uid,'TFA-Charge',$tempTxnid,$charge);
                }
            }
            if(isset($res->isFor))
            return response()->json(['status'=>'SUCCESS','type'=>'kyc','isFor'=>'transaction','message'=>'Two-factor authentication Verified successfully']);
        return response()->json(['status'=>'SUCCESS','type'=>'kyc','message'=>'Two-factor authentication Verified successfully']);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>$response->msg]);
        }
    }
    
    public function apTfaVerification(Request $res)
    {
        $uid = session('uid');
        $outlet = DB::table('aepskyc')->where('uid',$uid)->first()->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = 'https://partner.ecuzen.in/new/api/aeps/tfa';
        $sendingdata = array(
            'api_key' => $api_key,
            'outlet' => $outlet,
            'dsrno' => $res->dsrno,
            'pidtype' => $res->pidType,
            'piddata' => $res->pidData,
            'ci' => $res->ci,
            'dc' => $res->dc,
            'dpid' => $res->dpId,
            'errorcode' => $res->errorCode,
            'fcount' => $res->fCount,
            'ftype' => $res->fType,
            'hmac' => $res->hmac,
            'mc' => $res->mc,
            'mi' => $res->mi,
            'nmpoints' => $res->nmPoints,
            'qscore' => $res->qScore,
            'rdsid' => $res->rdsId,
            'rdsver' => $res->rdsVer,
            'sessionkey' => $res->sessionKey,
            'errorinfo' => $res->errorInfo,
            'service_type' => 'AP',
            );
          
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),['api-key' => $api_key]);
        // DB::table('test')->insert(['data'=>$response]);
        // return $response;
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $upData = array(
                'aptfa' => date('Y-m-d H:i:s', strtotime(Carbon::now())),
                'aptfaresponse' => json_encode($response)
                );
            $this->updateData('aepskyc','uid',$uid,$upData);
          if(isset($res->isFor))
            return response()->json(['status'=>'SUCCESS','type'=>'kyc','isFor'=>'transaction','message'=>'Two-factor authentication Verified successfully']);
        return response()->json(['status'=>'SUCCESS','type'=>'kyc','message'=>'Two-factor authentication Verified successfully']);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>$response->msg]);
        }
    }
    public function balanceenq(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'mobile' => 'required|numeric|digits:10',
                'aadhar' => 'required|numeric|digits:12',
                'dsrno' => 'required',
                'lat' => 'required',
                'log' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errors = $errors->first();
                return response()->json(['status'=>'ERROR','message' => $errors], 200);
            }
        $uid = session('uid');
        $aadhar = $res->aadhar;
        $mobile = $res->mobile;
        $bank = $res->bank;
        $check = $this->checkservice('aeps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'INFO','message'=>$check->message]);
        $outlet = DB::table('aepskyc')->where('uid',$uid)->first()->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partner.ecuzen.in/new/api/aeps/balance_enquery";
        $txnid="BE".strtoupper(uniqid());
        $sendingdata = array(
            'lat' => $res->lat,
            'log' => $res->log,
            'txnid' => $txnid,
            'api_key' => $api_key,
            'outlet_id' => $outlet,
            'dsrno' => $res->dsrno,
            'pidtype' => $res->pidType,
            'piddata' => $res->pidData,
            'ci' => $res->ci,
            'dc' => $res->dc,
            'dpid' => $res->dpId,
            'errorcode' => $res->errorCode,
            'fcount' => $res->fCount,
            'ftype' => $res->fType,
            'hmac' => $res->hmac,
            'mc' => $res->mc,
            'mi' => $res->mi,
            'nmpoints' => $res->nmPoints,
            'qscore' => $res->qScore,
            'rdsid' => $res->rdsId,
            'rdsver' => $res->rdsVer,
            'sessionkey' => $res->sessionKey,
            'adhaarno' => $aadhar,
            'bankcode' => $bank,
            'mobile' => $mobile,
            'errorinfo' => $res->errorInfo,
            'biom_data' => json_encode($res->newFingerData)
            );
           
        $response = $this->callApi($url,'POST',$sendingdata,'');
       
        
        #failed response
        // $response = '{"status":"FAILED","msg":"Customer  Aadhaar number is not linked with Selected Bank. Please check with the bank or select another Aadhaar linked bank","bank_ref":"234510440466","txnid":"BE4134751607"}';
        #success response
        // $response = '{"status":"SUCCESS","msg":"Request Completed","bank_ref":"234510441127","balance":30069.94,"txnid":"BE9504555759"}';
        
        
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $insertData = array(
                'uid' => $uid,
                'type' => 'BE',
                'txnid' => $response->txnid,
                'amount' => round($response->balance,2),
                'status' => $response->status,
                'message' => $response->msg,
                'aadhar' => 'XXXXXXXX'.substr($aadhar,-4),
                'rrn' => $response->bank_ref,
                'bank' => $bank,
                'mobile' => $mobile,
                'response' => json_encode($response)
                );
            $this->insertdata('iaepstxn',$insertData);
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.aeps.be',$insertData)->render()]);
        }
        else
        {
            $insertData = array(
                'uid' => $uid,
                'type' => 'BE',
                'txnid' => isset($response->txnid)?$response->txnid:'BETEMP'.rand(1000000000,9999999999),
                'amount' => isset($response->balance)?$response->balance:'NA',
                'status' => $response->status,
                'message' => $response->msg,
                'aadhar' => 'XXXXXXXX'.substr($aadhar,-4),
                'rrn' => isset($response->bank_ref)?$response->bank_ref:'TEMP'.rand(1000000000,9999999999),
                'bank' => $bank,
                'mobile' => $mobile,
                'response' => json_encode($response)
                );
            $this->insertdata('iaepstxn',$insertData);
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.aeps.be',$insertData)->render()]);
        }
    }
    
    public function miniStatement(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'mobile' => 'required|numeric|digits:10',
                'aadhar' => 'required|numeric|digits:12',
                'dsrno' => 'required',
                'lat' => 'required',
                'log' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errors = $errors->first();
                return response()->json(['status'=>'ERROR','message' => $errors], 200);
            }
        $uid = session('uid');
        $aadhar = $res->aadhar;
        $mobile = $res->mobile;
        $bank = $res->bank;
        $check = $this->checkservice('aeps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'INFO','message'=>$check->message]);
        $user = User::find($uid);
        $package = $user->package;
        $commissionRow = DB::table('mscom')->where('package',$package)->first();
        if($commissionRow == "" || $commissionRow == null)
        return response()->json(['status'=>'INFO','message'=>'Commission is not set for you please contact to admin!']);
        if($commissionRow->percent == 1)
        $commission = $commissionRow->amount/100;
        else
        $commission = $commissionRow->amount;
        $outlet = DB::table('aepskyc')->where('uid',$uid)->first()->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partner.ecuzen.in/new/api/aeps/mini_statement";
        $txnid="MS".strtoupper(uniqid());
        $sendingdata = array(
            'lat' => $res->lat,
            'log' => $res->log,
            'api_key' => $api_key,
             'txnid' => $txnid,
            'outlet_id' => $outlet,
            'dsrno' => $res->dsrno,
            'pidtype' => $res->pidType,
            'piddata' => $res->pidData,
            'ci' => $res->ci,
            'dc' => $res->dc,
            'dpid' => $res->dpId,
            'errorcode' => $res->errorCode,
            'fcount' => $res->fCount,
            'ftype' => $res->fType,
            'hmac' => $res->hmac,
            'mc' => $res->mc,
            'mi' => $res->mi,
            'nmpoints' => $res->nmPoints,
            'qscore' => $res->qScore,
            'rdsid' => $res->rdsId,
            'rdsver' => $res->rdsVer,
            'sessionkey' => $res->sessionKey,
            'adhaarno' => $aadhar,
            'bankcode' => $bank,
            'mobile' => $mobile,
            'errorinfo' => $res->errorInfo
            );
        $response = $this->callApi($url,'POST',$sendingdata,'');
        #failed response
        // $response = '{"status":"FAILED","msg":"Invalid aadhar number","bank_ref":null,"txnid":"MS2118902635"}';
        #success response
        // $response = '{"status":"SUCCESS","msg":"Request Completed","bank_ref":"320913189029","mini_statement":[{"date":"24\/09\/2022","txnType":"Cr","amount":"57.71","narration":" BY TRANSFER   "},{"date":"25\/09\/2022","txnType":"Cr","amount":"3.0","narration":" CREDIT INTERES"},{"date":"10\/11\/2022","txnType":"Cr","amount":"57.71","narration":" BY TRANSFER   "},{"date":"06\/12\/2022","txnType":"Dr","amount":"500.0","narration":" FI Txn @ CSP o"},{"date":"22\/12\/2022","txnType":"Cr","amount":"57.71","narration":" BY TRANSFER   "},{"date":"25\/12\/2022","txnType":"Cr","amount":"3.0","narration":" CREDIT INTERES"},{"date":"02\/02\/2023","txnType":"Cr","amount":"57.71","narration":" BY TRANSFER   "},{"date":"03\/02\/2023","txnType":"Cr","amount":"57.71","narration":" BY TRANSFER   "}],"txnid":"MS3254938446","balance":132.69999999999998863131622783839702606201171875}';
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $insertData = array(
                'uid' => $uid,
                'type' => 'MSI',
                'txnid' => $response->txnid,
                'amount' => round($response->balance,2),
                'status' => $response->status,
                'message' => $response->msg,
                'aadhar' => 'XXXXXXXX'.substr($aadhar,-4),
                'rrn' => $response->bank_ref,
                'bank' => $bank,
                'mobile' => $mobile,
                'response' => json_encode($response)
                );
            $this->insertdata('iaepstxn',$insertData);
            $this->creditEntry($uid,'MSI-COMMISSION',$response->txnid,round($commission,2));
            $this->distributeComm($uid,'MSI-COMMISSION','mscom',0,$response->txnid,'');
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['ministatement'] = $response->mini_statement;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.aeps.msi',$insertData)->render()]);
        }
        else
        {
            $insertData = array(
                'uid' => $uid,
                'type' => 'MSI',
                'txnid' => isset($response->txnid)?$response->txnid:'MSITEMP'.rand(1000000000,9999999999),
                'amount' => isset($response->balance)?$response->balance:'NA',
                'status' => $response->status,
                'message' => $response->msg,
                'aadhar' => 'XXXXXXXX'.substr($aadhar,-4),
                'rrn' => isset($response->bank_ref)?$response->bank_ref:'TEMP'.rand(1000000000,9999999999),
                'bank' => $bank,
                'mobile' => $mobile,
                'response' => json_encode($response)
                );
            $this->insertdata('iaepstxn',$insertData);
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['ministatement'] = isset($response->mini_statement)?$response->mini_statement:null;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.aeps.msi',$insertData)->render()]);
        }
    }
    
    public function cashWithdrawal(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'mobile' => 'required|numeric|digits:10',
                'aadhar' => 'required|numeric|digits:12',
                'dsrno' => 'required',
                'lat' => 'required',
                'log' => 'required',
                'amount' => 'required|numeric|gt:0',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errors = $errors->first();
                return response()->json(['status'=>'ERROR','message' => $errors], 200);
            }
        $uid = session('uid');
        $aadhar = $res->aadhar;
        $mobile = $res->mobile;
        $bank = $res->bank;
        $amount = $res->amount;
        $user = User::find($uid);
        $check = $this->checkservice('aeps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'INFO','message'=>$check->message]);
        $commission = $this->checkcharge($amount,'icaepscomission',$user->package);
        if($commission != true)
        return response()->json(['status'=>'INFO','message'=>'Commission are not set for you please contact to admin!']);
        $outlet = DB::table('aepskyc')->where('uid',$uid)->first()->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partner.ecuzen.in/new/api/aeps/cash_withdrawal";
        $txnid="CW".strtoupper(uniqid());
        $sendingdata = array(
            'lat' => $res->lat,
            'log' => $res->log,
            'api_key' => $api_key,
            'txnid' => $txnid,
            'outlet_id' => $outlet,
            'dsrno' => $res->dsrno,
            'pidtype' => $res->pidType,
            'piddata' => $res->pidData,
            'ci' => $res->ci,
            'dc' => $res->dc,
            'dpid' => $res->dpId,
            'errorcode' => $res->errorCode,
            'fcount' => $res->fCount,
            'ftype' => $res->fType,
            'hmac' => $res->hmac,
            'mc' => $res->mc,
            'mi' => $res->mi,
            'nmpoints' => $res->nmPoints,
            'qscore' => $res->qScore,
            'rdsid' => $res->rdsId,
            'rdsver' => $res->rdsVer,
            'sessionkey' => $res->sessionKey,
            'adhaarno' => $aadhar,
            'bankcode' => $bank,
            'mobile' => $mobile,
            'errorinfo' => $res->errorInfo,
            'amount' => $res->amount,
            );
         $response = $this->callApi($url,'POST',$sendingdata,'');
        #failed response
        // $response = '{"status":"FAILED","msg":"Customer  Aadhaar number is not linked with Selected Bank. Please check with the bank or select another Aadhaar linked bank","bank_ref":"234510440466","txnid":"BE4134751607"}';
        #success response
       /* $response = '{"status":"SUCCESS","msg":"Request Completed","bank_ref":"320912728953","txnamount":"1550","txnid":"CW9785212695","Remaining_balance":31.410000000000000142108547152020037174224853515625,"uidai":"","message":"Request Completed","rrn":"320912728953"}';*/
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $insertData = array(
                'uid' => $uid,
                'type' => 'CW',
                'txnid' => $response->txnid,
                'amount' => round($response->Remaining_balance,2),
                'status' => $response->status,
                'message' => $response->msg,
                'aadhar' => 'XXXXXXXX'.substr($aadhar,-4),
                'rrn' => $response->bank_ref,
                'bank' => $bank,
                'mobile' => $mobile,
                'txnamount' => $response->txnamount,
                'response' => json_encode($response)
                );
            $this->insertdata('iaepstxn',$insertData);
            #wallet entry
            $this->creditEntry($uid,'CASH-WIDTHDRAWAL',$response->txnid,$response->txnamount);
            $this->creditEntry($uid,'CW-COMMISSION',$response->txnid,$commission);
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.aeps.cw',$insertData)->render()]);
        }
        else
        {
            $insertData = array(
                'uid' => $uid,
                'type' => 'CW',
                'txnid' => isset($response->txnid)?$response->txnid:'CWTEMP'.rand(1000000000,9999999999),
                'amount' => isset($response->Remaining_balance)?$response->Remaining_balance:'NA',
                'status' => $response->status,
                'message' => $response->msg,
                'aadhar' => 'XXXXXXXX'.substr($aadhar,-4),
                'rrn' => isset($response->bank_ref)?$response->bank_ref:'TEMP'.rand(1000000000,9999999999),
                'bank' => $bank,
                'mobile' => $mobile,
                'txnamount' => isset($response->txnamount)?$response->txnamount:$amount,
                'response' => json_encode($response)
                );
            $this->insertdata('iaepstxn',$insertData);
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.aeps.cw',$insertData)->render()]);
        }
    }
    
    public function aadharPay(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'mobile' => 'required|numeric|digits:10',
                'aadhar' => 'required|numeric|digits:12',
                'dsrno' => 'required',
                'lat' => 'required',
                'log' => 'required',
                'amount' => 'required|numeric|gt:0'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errors = $errors->first();
                return response()->json(['status'=>'ERROR','message' => $errors], 200);
            }
        $uid = session('uid');
        $user = User::find($uid);
        $aadhar = $res->aadhar;
        $mobile = $res->mobile;
        $bank = $res->bank;
        $amount = $res->amount;
        $check = $this->checkservice('aeps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'INFO','message'=>$check->message]);
        $charge = $this->checkcharge($amount,'icapcharge',$user->package);
        if($charge != true)
        return response()->json(['status'=>'INFO','message'=>'Charges are not set for you please contact to admin!']);
        $outlet = DB::table('aepskyc')->where('uid',$uid)->first()->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partner.ecuzen.in/new/api/aeps/aadhaar_pay";
          $txnid="AP".strtoupper(uniqid());
           return $outlet;
        $sendingdata = array(
            'lat' => $res->lat,
            'log' => $res->log,
            'txnid' => $txnid,
            'api_key' => $api_key,
            'outlet_id' => $outlet,
            'dsrno' => $res->dsrno,
            'pidtype' => $res->pidType,
            'piddata' => $res->pidData,
            'ci' => $res->ci,
            'dc' => $res->dc,
            'dpid' => $res->dpId,
            'errorcode' => $res->errorCode,
            'fcount' => $res->fCount,
            'ftype' => $res->fType,
            'hmac' => $res->hmac,
            'mc' => $res->mc,
            'mi' => $res->mi,
            'nmpoints' => $res->nmPoints,
            'qscore' => $res->qScore,
            'rdsid' => $res->rdsId,
            'rdsver' => $res->rdsVer,
            'sessionkey' => $res->sessionKey,
            'adhaarno' => $aadhar,
            'bankcode' => $bank,
            'mobile' => $mobile,
            'errorinfo' => $res->errorInfo,
            'amount' => $res->amount,
            );
           
            
            
        $response = $this->callApi($url,'POST',$sendingdata,'');
        #failed response
        // $response = '{"status":"FAILED","msg":"Insufficient fund","bank_ref":"320913598751","txnid":"AP4942156996"}';
        #success response
        /*$response = '{"status":"SUCCESS","msg":"Request Completed","bank_ref":"320913597136","txnamount":"1000","txnid":"AP1708924550","Remaining_balance":2379.40999999999985448084771633148193359375,"uidai":"XXXXXXXX9387"}';*/
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $insertData = array(
                'uid' => $uid,
                'type' => 'AP',
                'txnid' => $response->txnid,
                'amount' => round($response->Remaining_balance,2),
                'status' => $response->status,
                'message' => $response->msg,
                'aadhar' => 'XXXXXXXX'.substr($aadhar,-4),
                'rrn' => $response->bank_ref,
                'bank' => $bank,
                'mobile' => $mobile,
                'txnamount' => $response->txnamount,
                'response' => json_encode($response)
                );
            $this->insertdata('iaepstxn',$insertData);
            #wallet entry
            $this->creditEntry($uid,'AADHAR-PAY',$response->txnid,$response->txnamount);
            $this->debitEntry($uid,'AP-CHARGE',$response->txnid,$charge);
            $this->distributeCommission($uid,'AP-Commission','adhaarpaycharge',$response->txnamount,$response->txnid);
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.aeps.ap',$insertData)->render()]);
        }
        else
        {
            $insertData = array(
                'uid' => $uid,
                'type' => 'AP',
                'txnid' => isset($response->txnid)?$response->txnid:'CWTEMP'.rand(1000000000,9999999999),
                'amount' => isset($response->Remaining_balance)?$response->Remaining_balance:'NA',
                'status' => $response->status,
                'message' => $response->msg,
                'aadhar' => 'XXXXXXXX'.substr($aadhar,-4),
                'rrn' => isset($response->bank_ref)?$response->bank_ref:'TEMP'.rand(1000000000,9999999999),
                'bank' => $bank,
                'mobile' => $mobile,
                'txnamount' => isset($response->txnamount)?$response->txnamount:$amount,
                'response' => json_encode($response)
                );
            $this->insertdata('iaepstxn',$insertData);
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.aeps.ap',$insertData)->render()]);
        }
    }
    

    
    
}

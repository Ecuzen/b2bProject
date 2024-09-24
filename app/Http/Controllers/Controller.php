<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controller as BaseController;
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
#models 
use App\Models\User;
class Controller extends BaseController
{
    protected $txnCharge;
    protected $txnUserId;
    use AuthorizesRequests, ValidatesRequests;
    protected function checkservice($service,$uid)
    {
        #service from admin
        $check = DB::table('service')->where('id',1)->first()->$service;
        if($check != 1)
        return (object)['status'=>'ERROR','message'=>'Service provider down please contact to admin!!'];
        $check = DB::table('users')->where('id',$uid)->first()->$service;
        if($check != 1)
        return (object)['status'=>'ERROR','message'=>'Service is disabled for you please contact to admin!!'];
        return (object)['status'=>'SUCCESS'];
    }
    
    protected function checkwallet($uid)
    {
        return DB::table('users')->where('id',$uid)->first()->wallet;
    }
    
    protected function checkcharge($amount,$table,$package)
    {
        $amount = (int)$amount;
        $package = (int)$package;
        $charge = DB::table($table)->where('froma','<=',$amount)->where('toa','>=',$amount)->where('package',$package)->first();
        if($charge == "")
        return false;
        if($charge->percent == 1)
        {
            $this->txnCharge = $amount * ($charge->amount) / 100;
        }
        else {
            $this->txnCharge = $charge->amount;
        }
        return $this->txnCharge;
    }
    protected function checkmaxlimit($table,$package)
    {
        $maxlimit = DB::table($table)->where('package',$package)->max('toa');
        if($maxlimit == "")
        return false;
        return $maxlimit;
    }
    protected function callApi($url,$method,$data, $header)
    { 
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_SSL_VERIFYHOST=> false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_POSTFIELDS => $data,
        ));
        if($header != ''){
        curl_setopt($curl, CURLOPT_HTTPHEADER , array(
            'Content-Type: application/json',
          ));
        }
        $response = curl_exec($curl);
        curl_close($curl);
       
        if (!Str::contains($response, "\"status\":") && !Str::contains($response, "\"postpaid\":")) {
            $rData = array(
                'status' => 'ERROR',
                'msg' => 'Server timeout!!'
                );
            return json_encode($rData);
        }
        return $response;
    }
    protected function callApiwithHeader($url,$method,$sendingData,$newHeader)
    {
        $nh=[];
        foreach($newHeader as $k=>$v){
            $nh[]="$k:$v";
        }
        $headers=array_merge($nh,array(
            'Content-Type: application/json'
            ));
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $sendingData,
        CURLOPT_HTTPHEADER => $headers,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
       
        if (!Str::contains($response, "\"status\":") && !Str::contains($response, "\"postpaid\":") && !Str::contains($response, "\"success\":")) {
            $rData = array(
                'status' => 'ERROR',
                'msg' => 'Server timeout!!'
                );
            return json_encode($rData);
        }
        return $response;
    }
    protected function insertdata($table,$data)
    {
        $res = DB::table($table)->insert($data);
        if(!$res)
        return false;
        return true;
    }
    protected function updatewallet($uid,$amount)
    {
        DB::table('users')->where('id',$uid)->update(['wallet' => $amount]);
    }
    protected function debitEntry($uid,$type,$txnid,$amount)
    {
        $wallet = $this->checkwallet($uid);
        $walletentry = array(
                'uid' => $uid,
                'type' => $type,
                'txntype' => 'DEBIT',
                'remark' => 'Your wallet has been debited the sum of '.$amount,
                'txnid' => $txnid,
                'amount' => $amount,
                'opening' => $wallet,
                'closing' => $wallet - $amount,
                );
        DB::table('wallet')->insert($walletentry);
        $this->updatewallet($uid,$wallet - $amount);
        $data = [
            'date' => Carbon::now()->format('Y-m-d H:i:s'),
            'txnid' => $txnid,
            'amount' => $amount,
            'view' => 'mail.transaction.debit',
            'subject' => 'Debit alert',
            'logo' => DB::table('settings')->where('name','logo')->first()->value,
            'name' => DB::table('users')->where('id',$uid)->get()->pluck('name'),
            'message' => 'Your wallet has been debited the sum of '.$amount.' towards '.$type,
            ];
            $usermail = DB::table('users')->where('id',$uid)->get()->pluck('email');
            Mail::to($usermail)->send(new SendEmail($data));
    }
    protected function upliadFile($file) # do not correct spel mistake
    {
        $imagePath ="";
         if ($file->isValid()) {
                $imageName = uniqid().time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('images', $imageName, 'public');
                $imagePath = Storage::disk('public')->path('images/' . $imageName);
                $imagePath = str_replace('/www/wwwroot/','https://',$imagePath);
            }
            return $imagePath;
    }
    protected function updateData($table,$primary,$pvalue,$data)
    {
        DB::table($table)->where($primary,$pvalue)->update($data);
    }
    protected function creditEntry($uid,$type,$txnid,$amount)
    {
        $wallet = $this->checkwallet($uid);
        $walletentry = array(
                'uid' => $uid,
                'txn_uid' => $this->txnUserId??null,
                'type' => $type,
                'txntype' => 'CREDIT',
                'remark' => 'Your wallet has been credited the sum of '.$amount,
                'txnid' => $txnid,
                'amount' => $amount,
                'opening' => $wallet,
                'closing' => $wallet + $amount,
                );
        DB::table('wallet')->insert($walletentry);
        $this->updatewallet($uid,$wallet + $amount);
        $data = [
            'date' => Carbon::now()->format('Y-m-d H:i:s'),
            'txnid' => $txnid,
            'amount' => $amount,
            'view' => 'mail.transaction.credit',
            'subject' => 'Credit alert',
            'logo' => DB::table('settings')->where('name','logo')->first()->value,
            'name' => DB::table('users')->where('id',$uid)->get()->pluck('name'),
            'message' => 'Your wallet has been credited the sum of '.$amount.' towards '.$type,
            ];
            $usermail = DB::table('users')->where('id',$uid)->get()->pluck('email');
            Mail::to($usermail)->send(new SendEmail($data));
    }
    protected function txnvia()
    {
        return $getMode = DB::table('settings')->where('name','txnvia')->first()->value;
    }
    private function tableToBeTruncate()
    {
        $tableToBeTruncate = ['app','aepskyc','bank','app_dmt','bbpstxn','bbps_data','dmrtxn','iaepstxn','kyc','loginlog','otherservices','pancoupontxn','payoutaccount','psa','qtransfertxn','qtra_acc','qtra_otp','rechargetxn','register','test','ticket','topup','transaction','users','wallet','wallet_to_wallet'];
    }
    protected function distributeCommission($uid,$type,$table,$amount,$txnid)
    {
        $ownerRow = "";
        $txnUser = User::where('id',$uid)->select('id','package','owner','wallet')->get();
        $txnUser = $txnUser[0];
        $userCharge = $this->checkcharge($amount,$table,$txnUser->package);
        while($txnUser->owner != 0 && $txnUser->owner != "" &&  $txnUser->owner != null)
        {
            $this->txnUserId = $uid;
            $ownerRow = User::where('id',$txnUser->owner)->select('id','package','owner','wallet')->get();
            $ownerRow = $ownerRow[0];
            $txnuserCharge = $this->checkcharge($amount,$table,$ownerRow->package);
            $netCommission = (double) $userCharge - (double) $txnuserCharge;
            $this->creditEntry($ownerRow->id,$type,$txnid,round($netCommission,2));
            $userCharge = $txnuserCharge;
            $txnUser = $ownerRow;
        }
        return true;
    }
    protected function distributeComm($uid,$type,$table,$amount,$txnid,$operatorId)
    {
        $ownerRow = "";
        $txnUser = User::where('id',$uid)->select('id','package','owner','wallet')->get();
        $txnUser = $txnUser[0];
        $majorTable = ['comissionv2','bbps'];
        $minorTable = ['coponcharge','mscom','accountVerify'];
        if(in_array($table,$majorTable))
        {
            if($table == 'comissionv2')
            $operator = 'operator';
            else
            $operator = 'opid';
            $userCommissionRow = DB::table($table)->where($operator,$operatorId)->where('package',$txnUser->package)->first();
            if($userCommissionRow->percent == 1)
            $userCommission = $amount * ($userCommissionRow->amount/100);
            else
            $userCommission = $userCommissionRow->amount;
            while($txnUser->owner != 0 && $txnUser->owner != "" &&  $txnUser->owner != null)
            {
                $this->txnUserId = $uid;
                $ownerRow = User::where('id',$txnUser->owner)->select('id','package','owner','wallet')->get();
                $ownerRow = $ownerRow[0];
                $userCommissionRow = DB::table($table)->where($operator,$operatorId)->where('package',$ownerRow->package)->first();
                if($userCommissionRow->percent == 1)
                $commission = $amount * ($userCommissionRow->amount/100);
                else
                $commission = $userCommissionRow->amount;
                $netCommission = (double)$commission - (double) $userCommission;
                $this->creditEntry($ownerRow->id,$type,$txnid,round($netCommission,2));
                $userCommission = $commission;
                $txnUser = $ownerRow;
            }
        }
        else if(in_array($table,$minorTable))
        {
            $userCommissionRow = DB::table($table)->where('package',$txnUser->package)->first();
            if($userCommissionRow->percent == 1)
            $userCommission = $amount * ($userCommissionRow->amount/100);
            else
            $userCommission = $userCommissionRow->amount;
            while($txnUser->owner != 0 && $txnUser->owner != "" &&  $txnUser->owner != null)
            {
                $this->txnUserId = $uid;
                $ownerRow = User::where('id',$txnUser->owner)->select('id','package','owner','wallet')->get();
                $ownerRow = $ownerRow[0];
                $userCommissionRow = DB::table($table)->where('package',$ownerRow->package)->first();
                if($userCommissionRow->percent == 1)
                $commission = $amount * ($userCommissionRow->amount/100);
                else
                $commission = $userCommissionRow->amount;
                if($table == 'mscom')
                $netCommission = (double)$commission - (double) $userCommission;
                else
                $netCommission = (double)$userCommission - (double) $commission;
                $this->creditEntry($ownerRow->id,$type,$txnid,round($netCommission,2));
                $userCommission = $commission;
                $txnUser = $ownerRow;
            }
        }
        return true;
    }
    protected function pushAndroid($registration_id = null, $data = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        if(is_array($registration_id))
        {
            $fields = array(
                'registration_ids' => $registration_id,
                'data' => $data,
                'time_to_live' => 9000,
                'delay_while_idle' => true,
                'priority' => 'high',
                'ttl' => 3600,
                'timestamp' => 3600,
                'sound' => 'default'
            );
        }
        else
        {
            $fields = array(
                'to' => $registration_id,
                'data' => $data,
                'ttl' => 3600,
                'timestamp' => 3600,
                'sound' => 'default'
            );
        }
        
        $headers = array('Authorization' => 'key=AAAAWZoa1A8:APA91bGZaJBf4GB1b024xbKfO7iQHmoLgSD4aex66eCNUjR-n2_ecaEYt-8kywA5dSiHC1fKQAlnvwBQzkR6XHNQ_MzLgM71G4hfHK0YI2NACMWXlP5m5WG8Dy00RBfoSX6oJJgidSTw');
        $response = $this->callApiwithHeader($url,'POST',json_encode($fields),$headers);
        dd($response);
    }
    protected function makeCurlRequest($url, $method, $data, $headers = []) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
           ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }
}

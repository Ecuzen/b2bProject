<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class Psa extends Controller
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
        $data = ['title' => $title,'logo' => $logo];
        #check application
        $check = DB::table('psa')->where('uid',session('uid'));
        if($check->count() > 0)
        {
            if($check->first()->status == 'APPROVED')
            {
                $psaid = $check->first()->psaid;
                $user = DB::table('users')->where('id',session('uid'))->first();
                $coupanCharge  = DB::table('coponcharge')->where('package',$user->package)->first()->amount;
                $data['psaid'] = $psaid;
                $data['charge'] = $coupanCharge;
                return view('psa.buycoupan',$data);
            }
            else
            {
                return view('psa.pending',$data);
            }
        }
        else {
            $states = DB::table('icicstate')->get();
            $details = DB::table('users as u')->join('kyc','kyc.uid' ,'=','u.id')->select('kyc.*','u.name','u.phone','u.email')->where('u.id',session('uid'))->get();
            $data['states'] = $states;
            $details = json_decode($details,true);
            $data['details'] = $details[0];
            // return $data;
            return view('psa.register',$data);
        }
            
    }
    public function submitPsa(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'pincode' => 'required|numeric|digits:6',
                'address' => 'required|string',
                'slocation' => 'required|string',
                'pan' => 'required|string|size:10',
                'aadhar' => 'required|numeric|digits:12',
                'email' => 'required|email',
                'phone' => 'required|numeric|digits:10',
                'name' => 'required|string',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','data' =>$errors], 200);
            }
        $uid = session('uid');
        $check = $this->checkservice('uti',$uid);
        if($check->status == 'ERROR')
        return $check;
        $pincode = $res->pincode;
        $address = $res->address;
        $slocation = $res->slocation;
        $pan = $res->pan;
        $aadhar = $res->aadhar;
        $email = $res->email;
        $phone = $res->phone;
        $name = $res->name;
        $state = $res->state;
        #check for existing pan
        $checkPan = DB::table('psa')->where(['adhaar' => $aadhar, 'pan' => $pan])->orWhere('uid', session('uid'))->count();
        if($checkPan >0)
        {
            return response()->json(['status'=>'INFO','message'=>'You already applied for PSA ']);
        }
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $sendingdata= array(
            'psaid' => "ECZ".$phone,
            'mobile' => $phone,
            'email' =>$email,
            'shop' => $slocation,
            'name' => $name,
            'pan' => $pan,
            'pincode' => $pincode,
            'location' => $address,
            'adhaar' => $aadhar,
            'state' => $state
            );
        $url = 'https://partners.ecuzen.in/api/pan/register';
        $headers = array('api-key' => $api_key);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        // $response = '{"status":"SUCCESS","message":null,"psa_status":"PENDING","psa_id":"ECZ9785800039"}';
        $response =  json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $sendingdata['uid'] = session('uid');
            Arr::forget($sendingdata, 'api_key');
            $insData = $sendingdata;
            $insData['status'] = $response->psa_status;
            $res = $this->insertdata('psa',$insData);
            return response()->json(['status'=>'SUCCESS','message'=>'Your application processed successfully your PSAID is :-'.$response->psa_id]);
        }
        else
        {
            return response()->json(['status'=>'INFO','message'=>"Can't proceed your request now please try after 5 minutes"]);
        }
    }
    public function buy(Request $res)
    {
        $uid = session('uid');
        $check = $this->checkservice('uti',$uid);
        if($check->status == 'ERROR')
        return $check;
        $qty = $res->quantity;
        #get coupan price 
        $user = DB::table('users')->where('id',$uid)->first();
        $coupanCharge  = DB::table('coponcharge')->where('package',$user->package)->first()->amount;
        $totalCharge = $coupanCharge * $qty;
        #check user wallet 
        $wallet = $this->checkwallet($uid);
        if($wallet < $totalCharge)
        return response()->json(['status'=>'INFO','message'=>'Insufficient fund in your wallet please recharge your wallet!!']);
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $psaid = DB::table('psa')->where('uid',$uid)->first()->psaid;
        $sendingdata = array(
            'api_key' => $api_key,
            'psa_id' => $psaid,
	        'quantity' => $qty
            );
        $url = 'https://partner.ecuzen.in/api/requestcopon';
        $response = $this->callApi($url,'POST',$sendingdata,'');
        $response = json_decode($response);
        if($response->status == 'SUCCESS' || $response->status == 'PENDING')
        {
            $insertData = array(
                'uid' => $uid,
                'txnid' => $response->txnid,
                'qty' => $qty,
                'amount' => $totalCharge,
                'status' => $response->status,
                'response' => json_encode($response),
                'message' => null,
                );
            $this->insertdata('pancoupontxn',$insertData);
            $this->debitEntry($uid,'UTI',$response->txnid,$totalCharge);
            // $this->distributeComm($uid,'UTI-COMMISSION','coponcharge',0,$response->txnid,'');
            $data = array(
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'txnid' => $response->txnid,
                'status' => $response->status,
                'item1' => 'Quantity',
                'item2' => 'Total',
                'amount1' => $qty,
                'amount2' => $totalCharge,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                );
            return response()->json(['status'=>'SUCCESS','view'=>view('receipt.uti',$data)->render()],200);
        }
        else
        {
            $insertData = array(
                'uid' => $uid,
                'txnid' => $response->txnid??'NA',
                'qty' => $qty??'NA',
                'amount' => $totalCharge??'NA',
                'status' => $response->status??'NA',
                'response' => json_encode($response)??'NA',
                'message' => $response->msg??'NA',
                );
            $this->insertdata('pancoupontxn',$insertData);
            return response()->json(['status'=>'ERROR','message'=>$response->msg]);
        }
    }
}

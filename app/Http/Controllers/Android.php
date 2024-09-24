<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;
#models section
use App\Models\User;
use App\Models\Register;
use App\Models\App;
use App\Models\Setting;
use App\Models\Aeps;
use Illuminate\Database\QueryException as Qx;



class Android extends Controller
{
	protected $pomode = 'pin';
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
    
	public function login(Request $res)
	{
		/*if(!$res->isJson())
        return response()->json(['status'=>'ERROR','message'=>'Invalid request format!','error'=>'false']);*/
		$validator = Validator::make($res->all(), [
			'phone' => 'required|numeric|regex:/^[1-9]{1}[0-9]{9}$/',
			'password' => 'required|string',
			'imei' => 'required|string',
			'device' => 'required|string',
		]);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return response()->json(['status' => 'ERROR', 'info' => $errors->first(), 'message' => 'Validation error!!', 'error' => false], 400);
		}
		$phone = $res->phone;
		$password = $res->password;
		$imei = $res->imei;
		$device = $res->device;
		$this->pomode = $res->ltype;
		// $lat = $res->lat;
		// $log = $res->log;
		#check for phone number
		$checkPhone = DB::table('users')->where('phone', $phone)->count();
		if ($checkPhone != 1) {
			return response()->json(['status' => 'ERROR', 'error' => false, 'message' => 'No User is associated with this phone number!'], 422);
		}
		#check for password
		$user = User::where('phone', $phone)->first();
		$checkPassword = $user->password;
		if ($checkPassword != $password) {
			return response()->json(['status' => 'ERROR', 'payload' => 'Invalid Password', 'message' => 'Wrong password!', 'error' => false], 401);
		}
		if ($this->pomode == 'pin') {
			$otp = $user->pin;
			$method = 'pin';
			$sendmsg = 'Please Enter your PIN to verify Login';
		} else {
			$otp = rand(100000, 999999);
			$method = 'otp';
			$sendmsg = 'Please Enter 6 Digit OTP shared to your mobile number!!';
		}
		$temp_key = uniqid();
		#check for existance or not 
		$checkAlready = App::where('uid', $user->id)->count();
		if ($checkAlready == 0) {
			$data = array(
				'uid' => $user->id,
				'temp_key' => $temp_key,
				'phone' => $phone,
				'method' => $method,
				'otp' => $otp,
				'imei' => $imei,
				'device' => $device,
				// 'lat' => $lat,
				// 'log' => $log,
				'ip' => $res->ip(),
			);
			$app = new App();
			$app->fill($data);
			$res = $app->save();
			if ($res)
				return response()->json(['status' => 'SUCCESS', 'message' => $sendmsg, 'mode' => $method, 'log_key' => $temp_key, 'error' => false], 200);
			else
				return response()->json(['status' => 'ERROR', 'message' => 'Not Implemented', 'error' => false], 501);
		} else {
			$data = array(
				'temp_key' => $temp_key,
				'method' => $method,
				'otp' => $otp,
				'imei' => $imei,
				'device' => $device,
				// 'lat' => $lat,
				// 'log' => $log,
				'ip' => $res->ip(),
			);
			$res = App::where('uid', $user->id)->update($data);
			if ($res)
				return response()->json(['status' => 'SUCCESS', 'message' => $sendmsg, 'mode' => $method, 'log_key' => $temp_key, 'error' => false], 200);
			else
				return response()->json(['status' => 'ERROR', 'message' => 'Not Implemented', 'error' => false], 501);
		}
	}
	public function getRole()
	{
		$roles = DB::table('role')->select('id', 'name')->get();
		$data = array(
			'roles' => $roles
		);
		return response()->json(['status' => 'SUCCESS', 'error' => false, 'message' => 'Roles fetched successfully!!', 'data' => $data], 200);
	}
	public function rdName()
	{
		$roles = DB::table('rdservice')->select('id', 'name')->get();
		$data = array(
			'rdname' => $roles
		);
		return response()->json(['status' => 'SUCCESS', 'error' => false, 'message' => 'Rd services fetched successfully!!', 'data' => $data], 200);
	}
	public function register(Request $res)
	{
		$validator = Validator::make($res->all(), [
			'phone' => 'required|numeric|unique:users,phone|regex:/^[1-9]{1}[0-9]{9}$/',
			'name' => 'required|string',
			'email' => 'required|email|unique:users,email',
			'role' => 'required|numeric',
		]);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return response()->json(['status' => 'ERROR', 'info' => $errors, 'message' => $errors->first(), 'error' => false], 400);
		}
		$phone = $res->phone;
		$email = $res->email;
		$name = $res->name;
		$role = $res->role;
		#check for existance
		$checkPhone = User::where('phone', $phone)->count();
		if ($checkPhone != 0)
			return response()->json(['status' => 'ERROR', 'error' => false, 'message' => 'Phone number already exists!'], 422);
		$checkEmail = User::where('email', $email)->count();
		if ($checkEmail != 0)
			return response()->json(['status' => 'ERROR', 'error' => false, 'message' => 'Email Id already exists!'], 422);
		$checkPhone = Register::where('mobile', $phone)->count();
		if ($checkPhone != 0)
			return response()->json(['status' => 'ERROR', 'error' => false, 'message' => 'Phone number already exists!'], 422);
		$checkEmail = Register::where('email', $email)->count();
		if ($checkEmail != 0)
			return response()->json(['status' => 'ERROR', 'error' => false, 'message' => 'Email Id already exists!'], 422);
		$name = explode(" ", $name);
		$first = $name[0];
		$last = array_slice($name, 1);
		$last =  implode(" ", $last);
		$data = array(
			'first' => $first,
			'last' => $last,
			'mobile' => $phone,
			'email' => $email,
			'role' => $role,
			'ip' => $res->ip(),
			'ref_by' => '0'
		);
		$register = new Register();
		$register->fill($data);
		$res = $register->save();
		if ($res)
			return response()->json(['status' => 'SUCCESS', 'error' => false, 'message' => 'Registration successfull!'], 200);
		else
			return response()->json(['status' => 'SUCCESS', 'error' => false, 'message' => 'Not implemented!'], 501);
	}
	public function getBankList()
	{
		$roles = DB::table('banks')->select('id', 'name')->get();
		$data = array(
			'bank' => $roles
		);
		return response()->json(['status' => 'SUCCESS', 'error' => false, 'message' => 'Rd services fetched successfully!!', 'data' => $data], 200);
	}
	public function loginVerify(Request $res)
	{
		$validator = Validator::make($res->all(), [
			'log_key' => 'required|string',
			'otp' => 'required|numeric',
		]);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return response()->json(['status' => 'ERROR', 'info' => $errors->first(), 'message' => 'Validation error!!', 'error' => false], 400);
		}
		$logKey = $res->log_key;
		$otp = $res->otp;
		$getRow = App::where('temp_key', $logKey)->first();
		if ($getRow == "" || $getRow == null)
			return response()->json(['status' => 'ERROR', 'message' => 'Invalid request!', 'error' => false], 400);
		if ($otp != $getRow->otp)
			return response()->json(['status' => 'ERROR', 'message' => 'Invalid ' . $getRow->method . '!', 'error' => false], 400);
		$userRow = User::find($getRow->uid);
		#create header token and header key 
		$token = Str::random(30);
		$key = Str::random(30);
		App::where('temp_key', $logKey)->update(['api_key' => $key, 'api_secret' => $token]);
		#check kyc 
		$checkKyc = DB::table('kyc')->where('uid', $userRow->id)->first();
		if ($checkKyc == "" || $checkKyc == null) {
			$kycStatus = 'NO';
		} else {
			if ($checkKyc->active == 0) {
				$kycStatus = 'PENDING';
			} else {
				$kycStatus = 'YES';
			}
			$kycData = array(
				'supportmail' => Setting::where('name', 'cemail')->first()->value,
				'supportphone' => Setting::where('name', 'cnumber')->first()->value,
				'shopname' => $checkKyc->shopname,
				'shopaddress' => $checkKyc->shopaddress,
				'address' => $checkKyc->address,
				'district' => $checkKyc->district,
				'state' => DB::table('icicstate')->where('id', $checkKyc->state)->first()->name,
				'pin' => $checkKyc->pincode,
				'dob' => $checkKyc->dob,
			);
		}
		$kycData['kyc'] = $kycStatus;
		$data = array(
			'accountkycstatus' => $kycData
		);
		return response()->json(['status' => 'SUCCESS', 'message' => 'Login successfully', 'data' => $data, 'header_token' => $token, 'header_key' => $key, 'error' => false], 200);
	}
	public function logout(Request $res)
	{
	    $uid = $res->uid;
	    App::where('uid',$uid)->update(['api_key'=>null,'api_secret'=>null,'temp_key'=>null]);
	    return response()->json(['status'=>'SUCCESS','message'=>'Logout successfully!!','error'=>true]);
	}
	public function getAllstate()
	{
		$state = DB::table('icicstate')->orderBy('name', 'ASC')->select('id', 'name')->get();
		$data = array(
			'state' => $state
		);
		return response()->json(['status' => 'SUCCESS', 'data' => $data, 'message' => 'All states are fetched successfully', 'error' => false], 200);
	}
	public function userForgot(Request $res, $type)
	{
		$blockedUsers = ['password', 'pin'];
		if (!in_array($type, $blockedUsers)) {
			return abort(404);
		}
		$validator = Validator::make($res->all(), [
			'email' => 'required|email|unique:users,email',
		]);
		if ($validator->fails()) {
			$email = $res->email;
			$userRow = User::where('email', $email)->first();
			$token = Str::random(60);
			#check if email exist 
			$checkExistance = DB::table('password_reset_tokens')->where('email', $email)->count();
			if ($checkExistance <= 0) {
				$insertData = array(
					'email' => $email,
					'token' => $token
				);
				DB::table('password_reset_tokens')->insert($insertData);
			} else {
				DB::table('password_reset_tokens')->where('email', $email)->update(['token' => $token]);
			}
			if ($type == 'password')
				$resetLink = url('/reset/password/' . $token);
			else
				$resetLink = url('/reset/pin/' . $token);
			$data = [
				'view' => 'mail.password.forget',
				'subject' => ucfirst($type) . ' reset request',
				'logo' => DB::table('settings')->where('name', 'logo')->first()->value,
				'company' => DB::table('settings')->where('name', 'title')->first()->value,
				'type' => ucfirst($type),
				'reset_link' => $resetLink
			];
			Mail::to($email)->send(new SendEmail($data));
			return response()->json(['status' => 'SUCCESS', 'message' => $type . ' reset link send to your registered email address', 'error' => false]);
		} else {
			return response()->json(['status' => 'ERROR', 'message' => 'This email id is not associated with any user!', 'error' => false],400);
		}
	}
	
	public function submitKyc(Request $res)
	{
		$validator = Validator::make($res->all(), [
			'aadharNumber' => 'required|numeric|digits:12',
			'aadharFront' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
			'aadharBack' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
			'panImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
			'panNumber' => 'required|string',
			'address' => 'required|string',
			'dob' => 'required|date',
			'shopName' => 'required|string',
			'pinCode' => 'required|numeric|digits:6',
			'district' => 'required|string',
			'shopAddress' => 'required|string',
			'fatherName' => 'required|string',
		]);
		if ($validator->fails()) {
			$errors = $validator->errors()->first();
			return response()->json(['status' => 'ERROR', 'message' => $errors, 'error' => false], 400);
		}
// 		return $res;
		$user = $res->user();
		$uid = $user->id;
		// return $uid;
		$aadhar = $res->aadharNumber;
		$check = DB::table('kyc')->where('adhaar', $aadhar)->orWhere('uid', $uid)->count();
		if ($check != 0)
			return response()->json(['status' => 'ERROR', 'message' => 'You already fill your KYC please wait untill admin approve your kyc!', 'error' => false], 422);
		$address = $res->address;
		$pincode = $res->pinCode;
		$pan = $res->panNumber;
		$adhaarimg = $this->upliadFile($res->file('aadharFront'));
		$panimg = $this->upliadFile($res->file('panImage'));
		$shopname = $res->shopName;
		$shopaddress = $res->shopAddress;
		$state = $res->state;
		$district = $res->district;
		$adhaarback = $this->upliadFile($res->file('aadharBack'));
		$fname = $res->fatherName;
		$dob = $res->dob;
		$sendingData = array(
			'uid' => $uid,
			'address' => $address,
			'pincode' => $pincode,
			'pan' => strtoupper($pan),
			'adhaar' => $aadhar,
			'adhaarimg' => $adhaarimg,
			'panimg' => $panimg,
			'shopname' => $shopname,
			'shopaddress' => $shopaddress,
			'active' => 0,
			'state' => $state,
			'district' => $district,
			'adhaarback' => $adhaarback,
			'fname' => $fname,
			'dob' => $dob
		);
		$res = $this->insertdata('kyc', $sendingData);
		if ($res) {
			$checkKyc = DB::table('kyc')->where('uid', $user->id)->first();
			$kycData = array(
				'supportmail' => Setting::where('name', 'cemail')->first()->value,
				'supportphone' => Setting::where('name', 'cnumber')->first()->value,
				'shopname' => $checkKyc->shopname,
				'shopaddress' => $checkKyc->shopaddress,
				'address' => $checkKyc->address,
				'district' => $checkKyc->district,
				'state' => DB::table('icicstate')->where('id', $checkKyc->state)->first()->name,
				'pin' => $checkKyc->pincode,
				'dob' => $checkKyc->dob,
				'kyc' => 'PENDING'
			);
			$data = array(
				'accountkycstatus' => $kycData
			);
			return response()->json(['status' => 'SUCCESS', 'message' => 'You KYC has been completed please wait for admin to approve your KYC request!!', 'data' => $data, 'error' => false]);
		}
		return response()->json(['status' > 'ERROR', 'message' => 'Database errors', 'error' => false],500);
	}
	function contact_data()
	{
		$required = ['title', 'logo', 'cnumber', 'cemail', 'news', 'address', 'facebook', 'youtube', 'twitter', 'instagram', 'linkedin'];
		$data = DB::table('settings')->whereIn('name', $required)->get();
		$f = [];
		foreach ($data as $d) {
			$f[$d->name] = $d->value;
		}
		return response()->json(['status' => 'SUCCESS', 'message' => 'Data fetched', 'data' => ['support' => $f]]);
	}
	
	function view_profile(Request $req)
	{
		//data->userprofile /$kycData
		$user = $req->user();
		$kyc = $user->kyc;
		if ($kyc != null) {
			$kyc->makeHidden(['id', 'uid']);
		}
		return response()->json([
			'status' => 'SUCCESS', 'message' => 'user profile fetched', 'data' =>
			[
				'kyc' => $kyc,
				'profile' => [
					'username' => $user->username, 'name' => $user->name,'profile' => $user->profile, 'mobile' => $user->phone, 'email' => $user->email
				]
			]
		]);
	}
	
	function update_user_location(Request $req)
	{
		$appdata = $req->appdata;
		if ($appdata == null) {
			return response()->json(['status' => 'ERROR', 'message' => 'Login details not found'], 400);
		}
		$validator = Validator::make($req->all(), [
			'lat' => 'required|numeric|between:-90,90',
			'long' => 'required|numeric|between:-180,180',
			'device_id' => 'required'
		], [
			'lat.between' => 'The latitude must be in range between -90 and 90',
			'long.between' => 'The longitude must be in range between -180 and 180'
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(),'info'=>$validator->errors()], 400);
		}
		try {
			DB::table('app')->where('id', $appdata->id)->update([
				'lat' => $req->lat,
				'log' => $req->long,
				'device_id' => $req->device_id,
				'device_token' => $req->device_token??null,
				'device_type' => $req->device_type??null
			]);
			return response()->json(['status' => 'SUCCESS', 'message' => 'updated successfully']);
		} catch (\Illuminate\Database\QueryException $e) {
			// return $e->getMessage(); //for debugging only
			return response()->json(['status' => 'ERROR', 'message' => 'Unknown error occured'], 500);
		}
	}
	function update_password(Request $req)
	{
		$validator = Validator::make($req->all(), [
			'current_password' => 'required',
			'new_password' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first()]);
		}
		$user = $req->user();
		if ($user->password != $req->current_password) {
			return response()->json(['status' => 'ERROR', 'message' => 'Invalid password'], 403);
		}
		$user->password = $req->new_password;
		$user->save();
		return response()->json(['status' => 'SUCCESS', 'message' => "Password saved successfully"]);
	}
	function update_pin(Request $req)
	{
		$validator = Validator::make($req->all(), [
			'current_pin' => 'required|numeric',
			'new_pin' => 'required|numeric',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first()]);
		}
		$user = $req->user();
		if ($user->pin != $req->current_pin) {
			return response()->json(['status' => 'ERROR', 'message' => 'Invalid PIN'], 403);
		}
		try {
			$user->pin = $req->new_pin;
			$user->save();
			return response()->json(['status' => 'SUCCESS', 'message' => 'PIN Updated successfully']);
		} catch (Qx $e) {
			// return $e->getMessage();
			return response()->json(['status' => 'ERROR', 'message' => 'Unknown error occured'], 500);
		}
	}
	
// 	function update_profile(Request $req)
// 	{
// 		$validator = Validator::make($req->all(), [
// 			'name' => "required",
// 			'mobile' => "required|numeric",
// 			'email' => "required|email"
// 		]);
// 		if ($validator->fails()) {
// 			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors()], 400);
// 		}
// 		$user = $req->user();
// 		try {
// 			$user->name = $req->name;
// 			$user->phone = $req->mobile;
// 			$user->email = $req->email;
// 			$user->save();
// 			return response()->json(['status' => 'SUCCESS', 'message' => 'Profile updated successfully', 'data' => ['profile' => ['username' => $user->username, 'name' => $user->name, 'mobile' => $user->phone, 'email' => $user->email]]]);
// 		} catch (Qx $e) {
// 			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first()], 400);
// 		}
//}



	function update_profile(Request $req)
	{
		$validator = Validator::make($req->all(), [
			'name' => "required",
			'mobile' => "required|numeric",
			'profile' => "",
			'email' => "required|email"
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors()], 400);
		}
		$user = $req->user();
		$imageName = ''; 
		try {
		    
		     // Store the uploaded image
		     if ($req->hasFile('profile')) {
            $imageName = time() . uniqid() . '.' . $req->profile->extension();
            $req->profile->move(public_path('uploads'), $imageName);
        }
  
		    
			$user->name = $req->name;
			$user->phone = $req->mobile;
			$user->email = $req->email;
            if (!empty($imageName)) {
            $user->profile = env('APP_URL').'/public/uploads/' . $imageName;
        }

        $user->save();
			return response()->json(['status' => 'SUCCESS', 'message' => 'Profile updated successfully', 'data' => ['profile' => ['username' => $user->username, 'name' => $user->name,'profile' => $user->profile, 'mobile' => $user->phone, 'email' => $user->email]]]);
		} catch (Qx $e) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first()], 400);
		}
	}
	function wallet(Request$req)
	{
	    return response()->json(['status'=>'SUCCESS','message'=>'balance fetched','walletBalance'=>(double)$req->user()->wallet]);
	}
	function topup(Request $req)
	{
	    $validator=Validator::make($req->all(),[
	        'amount'=>'required|numeric',
	        'bank'=>'required|exists:bank,id',
	        'rrn'=>'required|unique:topup,rrn',
	        'txn_date'=>'required|date|before_or_equal:today',
	        'proof'=>'required|file|mimes:jpeg,png,jpg|max:200',
	        
	        ],[
	            'amount.required'=>'Amount is required',
	            'bank.required'=>'Bank is required',
	            'rrn.required'=>'RRN is required',
	            'txn_date.required'=>'Transaction Date is required',
	            'txn_date.before_or_equal'=>'The transaction date cannot be set in the future.',
	            'rrn.unique'=>'The RRN is either invalid or a request has already been made for this particular one.',
	            ]);
	        if($validator->fails()){
	            return response()->json(['status'=>"ERROR",'message'=>$validator->errors()->first(),'info'=>$validator->errors()],400);
	        }
	        $user=$req->user();
	        $txnid="TOPUP".strtoupper(uniqid());
	        try{
	            DB::table('topup')->insert([
	                'txnid'=>$txnid,
	                'amount'=>$req->amount,
	                'bank'=>$req->bank,
	                'rrn'=>$req->rrn,
	                'proof'=>$this->upliadFile($req->file('proof')),
	                'transaction_date'=>$req->txn_date,
	                'uid'=>$user->id,
	                'status'=>'PENDING'
	                ]);
	        }catch(Qx $e){
	            return response()->json(['status'=>'ERROR','message'=>'Unknown error occured, PLease try after some time','trackid'=>'IBUJUI897','data'=>['errorinfo'=>$e->getMessage()]],500);
	        }
	        return response(['status'=>'SUCCESS','message'=>'Request submitted','txnid'=>$txnid]);
	}
	public function getAllReportType()
	{
	    $data = DB::table('reportItems')->get();
	    return response()->json(['status'=>'SUCCESS','message'=>'Report items fetched successfully','data'=>['reportItems'=>$data],'error'=>false]);
	}
	public function getReport(Request $res)
	{
	    $validator = Validator::make($res->all(), [
			'from' => 'required|date',
			'to' => 'required|date',
			'type' => 'required|string'
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		
		$page_number = (!empty(request('page_number'))) ? request('page_number') : 1;
		$limit = (!empty(request('limit'))) ? request('limit') : 10;
		$offset = ($page_number-1) * $limit;
		
	    $type = $res->type;
	    $allowedTypes = ['aeps','money-transfer','qtransfer','payout','recharge','bbps','uti-coupon','wallet','wallet-to-wallet'];
	    if(!in_array($type,$allowedTypes))
	    return view('errors.404');
	    $uid = $res->uid;
        $from = date('Y-m-d H:i:s', strtotime(Carbon::parse($res->from)->startOfDay()));
        $to = date('Y-m-d H:i:s', strtotime(Carbon::parse($res->to)->endOfDay()));
        $orderType = 'desc';
        $search = $res->search??null;
        switch($type)
        {
            case 'aeps' :
                
                $Query = DB::table('iaepstxn')->where('uid',$uid);
                if(!empty($from) && !empty($to))
                {
                    $Query->whereBetween('date', [$from, $to]);   
                }
                
                if(!empty($search))
                {
                    $Query->where('txnid','like','%'.$search.'%');
                }
                $totalCount = $Query->count();
                $Query->orderBy('id', $orderType)->offset($offset)->take($limit);
                $txnData = $Query->get();
                if(!empty($txnData)) 
                {
                    foreach ($txnData as $tData)
                    {
                        $tData->bank = DB::table('banks')->where('id',$tData->bank)->first()->name;
                        if($tData->type == 'CW' || $tData->type == 'AP')
                        $tData->amount = $tData->txnamount;
                    }
                    return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','data'=>['aepsReport'=>$txnData],'error'=>false ,'total_record' => $totalCount]);
                }
                else
                {
                    return response()->json(['status'=>'SUCCESS','message'=>'No Transactions found!!','data'=>['aepsReport'=>[]],'error'=>false ,'total_record' => $totalCount]);
                }
                break;
            case 'money-transfer' :
                $txnData = DB::table('dmrtxn')->where('uid',$uid)->orderBy('id', $orderType);
                if($search == null)
                $txnData->whereBetween('date', [$from, $to]);
                else
                $txnData->where('txnid','like','%'.$search.'%');
                $totalCount = $txnData->count();
                $txnData = $txnData->offset($offset)->take($limit)->get();
                if($totalCount == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transactions found!!','data'=>['dmtReport'=>[]],'error'=>false,'total_record'=>$totalCount]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','data'=>['dmtReport'=>$txnData],'error'=>false,'total_record'=>$totalCount]);
                break;
            case 'qtransfer' :
                $txnData = DB::table('qtransfertxn')->where('uid',$uid)->orderBy('id', $orderType);
                if($search == null)
                $txnData->whereBetween('date', [$from, $to]);
                else
                $txnData->where('txnid','like','%'.$search.'%');
                $totalCount = $txnData->count();
                $txnData = $txnData->offset($offset)->take($limit)->get();
                if($totalCount == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transactions found!!','data'=>['qtransferReport'=>[]],'error'=>false,'total_record'=>$totalCount]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','data'=>['qtransferReport'=>$txnData],'error'=>false,'total_record'=>$totalCount]);
                break;
            case 'payout' :
                $txnData = DB::table('payouttxn')->where('uid',$uid)->orderBy('id', $orderType);
                if($search == null)
                $txnData->whereBetween('date', [$from, $to]);
                else
                $txnData->where('txnid','like','%'.$search.'%');
                $totalCount = $txnData->count();
                $txnData = $txnData->offset($offset)->take($limit)->get();
                if($totalCount == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transactions found!!','data'=>['payoutReport'=>[]],'error'=>false,'total_record'=>$totalCount]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','data'=>['payoutReport'=>$txnData],'error'=>false,'total_record'=>$totalCount]);
                break;
            case 'recharge' :
                $txnData = DB::table('rechargetxn')->where('uid',$uid)->orderBy('id', $orderType);
                if($search == null)
                $txnData->whereBetween('date', [$from, $to]);
                else
                $txnData->where('txnid','like','%'.$search.'%');
                $totalCount = $txnData->count();
                $txnData = $txnData->offset($offset)->take($limit)->get();
                if($totalCount == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transactions found!!','data'=>['rechargeReport'=>[]],'error'=>false,'total_record'=>$totalCount]);
                foreach ($txnData as $tData)
                {
                    $tData->operator = DB::table('rechargeop')->where('id',$tData->operator)->first()->name;
                }
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','data'=>['rechargeReport'=>$txnData],'error'=>false,'total_record'=>$totalCount]);
                break;
            case 'bbps' :
                $txnData = DB::table('bbpstxn')->where('uid',$uid)->orderBy('id', $orderType);
                if($search == null)
                $txnData->whereBetween('date', [$from, $to]);
                else
                $txnData->where('txnid','like','%'.$search.'%');
                $totalCount = $txnData->count();
                $txnData = $txnData->offset($offset)->take($limit)->get();
                if($totalCount == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transactions found!!','data'=>['bbpsReport'=>[]],'error'=>false,'total_record'=>$totalCount]);
                foreach ($txnData as $tData)
                {
                    $opData = DB::table('bbpsnewop')->where('id',$tData->boperator)->first();
                    $tData->boperator = $opData->name;
                    $tData->displayName = $opData->displayname;
                }
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','data'=>['bbpsReport'=>$txnData],'error'=>false,'total_record'=>$totalCount]);
                break;
            case 'uti-coupon' :
                $txnData = DB::table('pancoupontxn')->where('uid',$uid)->orderBy('id', $orderType);
                if($search == null)
                $txnData->whereBetween('date', [$from, $to]);
                else
                $txnData->where('txnid','like','%'.$search.'%');
                $totalCount = $txnData->count();
                $txnData = $txnData->offset($offset)->take($limit)->get();
                if($totalCount == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transactions found!!','data'=>['utiReport'=>[]],'error'=>false,'total_record'=>$totalCount]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','data'=>['utiReport'=>$txnData],'error'=>false,'total_record'=>$totalCount]);
                break;
            case 'wallet' :
                $txnData = DB::table('wallet')->where('uid',$uid)->orderBy('id', $orderType);
                if($search == null)
                $txnData->whereBetween('date', [$from, $to]);
                else
                $txnData->where('txnid','like','%'.$search.'%');
                $totalCount = $txnData->count();
                $txnData = $txnData->offset($offset)->take($limit)->get();
                if($totalCount == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transactions found!!','data'=>['walletReport'=>[]],'error'=>false,'total_record'=>$totalCount]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','data'=>['walletReport'=>$txnData],'error'=>false,'total_record'=>$totalCount]);
                break;
            case 'wallet-to-wallet' :
                $txnData = DB::table('wallet_to_wallet')->where('sender',$uid)->orWhere('receiver',$uid)->orderBy('id', $orderType);
                if($search == null)
                $txnData->whereBetween('date', [$from, $to]);
                else
                $txnData->where('txnid','like','%'.$search.'%');
                $totalCount = $txnData->count();
                $txnData = $txnData->offset($offset)->take($limit)->get();
                if($totalCount == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','data'=>['walletToWalletReport'=>[]],'error'=>false,'total_record'=>$totalCount]);
                $txns = array();
                foreach ($txnData as $txn)
                {
                    $sender = User::select('name','phone')->where('id',$txn->sender)->first();
                    $receiver = User::select('name','phone')->where('id',$txn->receiver)->first();
                    $latestTxns = array(
                        'senderName' => $sender->name,
                        'senderPhone' => $sender->phone,
                        'receiverName' => $receiver->name,
                        'receiverphone' => $receiver->phone,
                        'type' => $txn->type,
                        'amount' => $txn->amount,
                        'date' => $txn->date,
                        'txnid' => $txn->txnid
                        );
                    $txns[] = $latestTxns;
                }
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','data'=>['walletToWalletReport'=>$txns],'error'=>false,'total_record'=>$totalCount]);
                break;
            default:
                return view('errors.404');
        }
        return $txnData;
	}
	public function commissionPlans(Request $res)
	{
	    $user = $res->user();
	    $package = $user->package;
	    $tableData = DB::table('commtables')->select('id','table_name','bracket','label')->get();
	   // return $tableData;
	   $returnData = array();
	    foreach ($tableData as $tableData)
	    {
    	    $getDetails = DB::table($tableData->table_name)->where('package',$package)->get();
    	    if($tableData->bracket == 1)
    	    $getDetails = DB::table($tableData->table_name)->where('package',$package)->where('froma','!=',0)->get();
            if($tableData->id == 10)
            $getDetails = DB::table('comissionv2')->join('rechargeop', 'rechargeop.id', '=', 'comissionv2.operator')->where('comissionv2.package', '=', $package)->select("comissionv2.*","rechargeop.name as operator")->get();
            if($tableData->id == 13)
            $getDetails = DB::table('bbps')->join('bbps_categories', 'bbps_categories.id', '=', 'bbps.opid')->where('bbps.package', '=', $package)->select("bbps.*","bbps_categories.name as opid")->get();
            if (Schema::hasTable($tableData->table_name)) {
                $columns = Schema::getColumnListing($tableData->table_name);
            }
            $elementsToRemove = ["id", "commission", "package"];
            $newColumn = array_diff($columns,$elementsToRemove);
            $newColumn = array_values($newColumn);
            // return $newColumn;
            $data = array(
                'label' => $tableData->label,
                'column' => $newColumn,
                'tableData' => $getDetails,
                );
            $returnData[] = $data;
            // print_r($data);
	    }
	    return response()->json(['status'=>'SUCCESS','message'=>'Commission and charges slabs','data'=>['commssionSlab'=>$returnData],'error'=>false]);
	}
	function services(Request $req)
	{
		//aeps / bbps/ utility 
		$user = $req->user();
		$serviceR = DB::table('service')->first();
		$services = [];

		if ($serviceR->aeps == 1 && $user->aeps == 1) {
			$services['aeps'] = [
				['type' => 'cw', 'name' => "Cash\nWithdrawal", 'icon' => url('assets/dist/images/new-folder/aeps.png')],
				['type' => 'ap', 'name' => "Aadhaar\nPay", 'icon' => url('assets/dist/images/new-folder/aeps.png')],
				['type' => 'be', 'name' => "Balance\nEnquery", 'icon' => url('assets/dist/images/new-folder/aeps.png')],
				['type' => 'ms', 'name' => "Mini\nStatements", 'icon' => url('assets/dist/images/new-folder/aeps.png')],
			];
		}
		if ($serviceR->bbps == 1 && $user->bbps == 1) {
			$services['bbps'] = DB::table('bbps_categories')->where('id','!=',17)->select('name', 'icon', 'cat_key as type')->limit(8)->get();
		}
		if ($serviceR->recharge == 1 && $user->recharge == 1) {
			$services['utility'][] = ['type' => 'rech', 'name' => "Mobile Recharge", 'icon' => url('assets/dist/images/new-folder/recharge-blue.png')];
		}
		if ($serviceR->dmt == 1 && $user->dmt == 1) {
			$services['utility'][] = ['type' => 'dmt', 'name' => "Money Transfer", 'icon' => url('assets/dist/images/new-folder/Group%20302%20(1).png')];
		}
		if ($serviceR->payout == 1 && $user->payout == 1) {
			$services['utility'][] = ['type' => 'payout', 'name' => "Payout", 'icon' => url('assets/dist/images/new-folder/Group%20144%20(1).png')];
		}
		if ($serviceR->qtransfer == 1 && $user->qtransfer == 1) {
			$services['utility'][] = ['type' => 'qt', 'name' => "Quick Transfer", 'icon' => url('assets/dist/images/new-folder/Vector%20(3).png')];
		}
		if ($serviceR->uti == 1 && $user->uti == 1) {
			$services['utility'][] = ['type' => 'uti', 'name' => "UTI", 'icon' => url('assets/dist/images/new-folder/uti-blue.png')];
		}
		$services['other'][] = ['type' => 'pan', 'name' => "PAN Verification", 'icon' => url('assets/dist/images/new-folder/Vector%20(3).png')];
		$services['other'][] = ['type' => 'FT', 'name' => "FastTag", 'icon' => url('assets/dist/images/new-folder/Vector%20(3).png')];
		$services['other'][] = ['type' => 'CD', 'name' => "Credit Card Bill Payment", 'icon' => url('assets/dist/images/new-folder/Vector%20(3).png')];
		$services['other'][] = ['type' => 'TF', 'name' => "Itr Form", 'icon' => url('assets/dist/images/new-folder/Vector%20(3).png')];
		$baseUrl=url('/')."/";
		$services['banners']=DB::table('settings')->selectRaw("id,name,value as filepath")->whereIn('name',['banner1','banner2','banner3'])->get();
		// $user->refresh();
		$news = Setting::where('name', 'news')->pluck('value');
		
		if(!isset($services['bbps']))
		$services['bbps']=[];
		if(!isset($services['utility']))
		$services['utility']=[];
		if(!isset($services['aeps']))
		$services['aeps']=[];
		return response()->json(['status' => 'SUCCESS', 'message' => 'Services fetched', 'data' => $services, 'news' => $news[0], 'walletBalance' => round($user->wallet, 2)]);
	}
	
	
	
	
	public function bbpsAllBillers(Request $req)
    {
        $user = $req->user();
		$serviceR = DB::table('service')->first();
		$services = [];
		if ($serviceR->bbps == 1 && $user->bbps == 1) {
			$services['bbps'] = DB::table('bbps_categories')->where('id','!=',17)->select('name', 'icon', 'cat_key as type')->orderBy('name','asc')->get();
		}
		if(!isset($services['bbps']))
		$services['bbps']=[];
		return response()->json(['status' => 'SUCCESS', 'message' => 'Services fetched', 'data' => $services]);
    }
	function bbps_billers(Request $req)
	{
		$validator = Validator::make($req->all(), [
			'type' => 'required'
		]);
		
		if ($validator->fails()) {
			return response()->json(['status' => "ERROR", 'message' => $validator->errors()->first(), 'info' => $validator->errors()],400);
		}
		$billers = DB::table('bbps_all_billers')->select("bbps_all_billers.*", "category_key as type",'icon_url as icon')->where('category_key', $req->type)->get();
		if (count($billers) == 0) {
			return response()->json(['status' => 'ERROR', 'message' => 'Invalid type.'],404);
		}
		$filtered = [];
		foreach ($billers as $b) {
			// $c=$v;
			foreach($b as $k=>$v){
			    if($v==null){
			        $b->$k='';
			    }
			}
			$b->param1_type = $b->param1 != null ? $this->ptype($b->param1) : '';
			$b->param2_type = $b->param2 != null ? $this->ptype($b->param2) : '';
			$b->param3_type = $b->param3 != null ? $this->ptype($b->param3) : '';
			$b->param4_type = $b->param4 != null ? $this->ptype($b->param4) : '';
		}
		return response()->json(['status' => 'SUCCESS', 'message' => 'Services fetched', 'data' => ['billers' => $billers]]);
	}
	function bbps_fetch_bill(Request $res)
	{
		$data = $res->all();
        $getDetails = DB::table('bbps_all_billers')->where('id',$data['biller_id'])->first();
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
		$validator = Validator::make($res->all(), $rules, $messages);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return response()->json(['status' => 'ERROR', 'message' => $errors->first(), 'info' => $errors], 400);
		}
		$user = $res->user();
		$uid = $user->id;
        $check = $this->checkservice('bbps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message]);
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
        $response = $this->callApi($url,'POST',json_encode($sendingData),'p');
        // return $response;
		// return json_encode($sendingdata);
// 		$response='{"response_code":1,"status":true,"amount":"7919.0","name":"SH. MADHU BALLABH MISHRA","duedate":"2023-08-29","billdate":"19 Aug 2023","bill_fetch":{"billAmount":"7919.0","billnetamount":"7919.0","billdate":"19 Aug 2023","dueDate":"2023-08-29","acceptPayment":true,"acceptPartPay":false,"cellNumber":"210462053699","userName":"SH. MADHU BALLABH MISHRA"},"message":"Bill Fetched Success."}';
		$response = json_decode($response);
		if ($response->status != 'SUCCESS') {
			return response()->json(['status' => 'ERROR', 'message' => $response->msg],400);
		}
		$billData = $response->data;
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
            'paramname' => $paramdetails[0]['name'],
			'paramvalue' => $paramdetails[0]['value'],
        );
        
        
        
		/*$billdata = $response->billdata;
		$fetchdata = array(
			'message' => $response->msg,
			'name' => $billdata->name,
			'paramname' => $getDetails->displayname,
			'paramvalue' => $req->baseparam,
			'item1' => 'Bill Date',
			'item1value' => $billdata->billdate??'NA',
			'item2' => 'Due Date',
			'item2value' => $billdata->duedate??'NA',
			'item3' => 'Amount',
			'item3value' => $billdata->amount,
			'support' => DB::table('settings')->where('name', 'cemail')->first()->value,
		);*/
		try {
			$ongp = \App\Models\Ongp::create(['uid' => $user->id, 'data' => json_encode($billData)]);
			return response()->json(['status' => 'SUCCESS', 'message' => 'Bill fetched', 'data' => ['fetchdata'=>$fetchdata], 'skey' => $response->skey]);
		} catch (Qx $e) {
			return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 500);
		}
	}
	public function bbps_pay_bill(Request $res)
	{
	    try {
            $data = $res->all();
            $getDetails = DB::table('bbps_all_billers')->where('id',$data['biller_id'])->first();
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
                return response()->json(['status'=>'ERROR','info' => $errors,'message' => $errors->first()], 400);
            }
            // $amount = $res->amount;
            $amount = 1;
            $user = $res->user();
            $uid = $user->id;
            $user = DB::table('users')->where('id',$uid)->first();
            $check = $this->checkservice('bbps',$uid);
            if($check->status == 'ERROR')
            return response()->json(['status'=>'INFO','message'=>$check->message]);
            $wallet = $this->checkwallet($uid);
            if($wallet < $amount)
            return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],402);
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
            // $response = '{"status":"FAILED","msg":"Calculation configuration issue","txnid":"BBPS4287709606","data":{"uid":73,"bid":546,"txnid":"BBPS4287709606","amount":1,"commission":"1","status":"FAILED","date":"2023-12-04 08:06:41","id":1862,"response":"{\"statuscode\":\"ISE\",\"actcode\":null,\"status\":\"Calculation configuration issue\",\"data\":null,\"timestamp\":\"2023-12-04 13:36:42\",\"ipay_uuid\":\"h0069ac44283-8a14-4b80-89f4-6d38671ca735-ELcPfBN0pEuh\",\"orderid\":null,\"environment\":\"LIVE\",\"internalCode\":null}","msg":"Calculation configuration issue"}}';
            #success response
            /*$response = '{"status":"SUCCESS","msg":"Transaction Successful","txnid":"BBPS4592918907","data":{"uid":73,"bid":546,"txnid":"BBPS4592918907","amount":"332.00","commission":"1","status":"SUCCESS","date":"2023-12-04 10:09:07","id":1865,"response":"{\"statuscode\":\"TXN\",\"actcode\":null,\"status\":\"Transaction Successful\",\"data\":{\"externalRef\":\"BBPS4592918907\",\"poolReferenceId\":\"1231204153908FKJPU\",\"txnValue\":\"332.00\",\"txnReferenceId\":\"TJ01333803390895B72A\",\"pool\":{\"account\":\"8094947450\",\"openingBal\":\"1515.55\",\"mode\":\"DR\",\"amount\":\"330.70\",\"closingBal\":\"1184.85\"},\"billerDetails\":{\"name\":\"TP Central Odisha Distribution Ltd.\",\"account\":\"202S03695536\"},\"billDetails\":{\"CustomerName\":\" RAMESHA PARIDA\",\"BillNumber\":\"202311182312202S03695536\",\"BillPeriod\":\"1\",\"BillDate\":\"18\\\/11\\\/2023\",\"BillDueDate\":\"28\\\/11\\\/2023\",\"BillAmount\":\"332.00\",\"CustomerParamsDetails\":[{\"Name\":\"Consumer Id\",\"Value\":\"202S03695536\"}],\"AdditionalDetails\":null}},\"timestamp\":\"2023-12-04 15:39:10\",\"ipay_uuid\":\"h0689ac46e4c-0862-4388-8812-60a96dac1bcf-mlrQzxBnbvtH\",\"orderid\":\"1231204153908FKJPU\",\"environment\":\"LIVE\"}","msg":"Transaction Successful"}}';*/
            $response = json_decode($response);
            /*$fetchData = session()->get('fetchDetails') ?? [];
            session()->forget('fetchDetails');
            $paramdetails = [];
            foreach ($fetchData->CustomerParamsDetails as $data) {
                $paramdetails[] = ['name' => $data->Name, 'value' => $data->Value];
            }*/
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
                    // 'paramdetails' => $paramdetails,
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
            // return response()->json(['status'=>'SUCCESS','view'=>view('receipt.bbps_new',$data)->render(),'closing'=>$wallet+$commissionAmount - $amount],200);
            
            
            /*$data = array(
				'date' => Carbon::now()->format('Y-m-d H:i:s'),
				'name' => $name,
				'txnid' => $txnid,
				'status' => $status,
				'item1' => 'BBPS',
				'item2' => 'BBPS-COMMISSION',
				'amount1' => $amount,
				'amount2' => $commissionAmount,
				'total' => $amount - $commissionAmount,
				'support' => DB::table('settings')->where('name', 'cemail')->first()->value,
			);*/
			$latestTransactions = DB::table('bbpstxn')->join('bbps_all_billers','bbps_all_billers.id','=','bbpstxn.boperator')->where('uid',$user->id)->select('bbpstxn.txnid','bbpstxn.status','bbpstxn.amount','bbpstxn.canumber','bbpstxn.boperator','bbpstxn.date','bbps_all_billers.biller_name as operatorName','bbps_all_billers.param1')->orderBy('bbpstxn.id', 'desc')->take(5)->get();
			$data = array(
			    'bbpsrecent' => $latestTransactions
			    );
			return response()->json(['status' => 'SUCCESS', 'message' => 'Transaction SUCCESS', 'walletBalance' =>(double) $wallet + $commissionAmount - $amount,'data'=>$data], 200);
            }
            else
            {
                $updateData = array(
                    'status' => $response->status,
                    'response' => json_encode($response),
                    );
                DB::table('bbpstxn')->where('txnid', $temptxnid)->update($updateData);
                $this->creditEntry($uid,'BBPS-REFUND',$temptxnid,$amount);
                $latestTransactions = DB::table('bbpstxn')->join('bbps_all_billers','bbps_all_billers.id','=','bbpstxn.boperator')->where('uid',$user->id)->select('bbpstxn.txnid','bbpstxn.status','bbpstxn.amount','bbpstxn.canumber','bbpstxn.boperator','bbpstxn.date','bbps_all_billers.biller_name as operatorName','bbps_all_billers.param1')->orderBy('bbpstxn.id', 'desc')->take(5)->get();
			$data = array(
			    'bbpsrecent' => $latestTransactions
			    );
			return response()->json(['status' => $response->status, 'message' => $response->msg??'NA', 'walletBalance' => (double)$user->wallet ,'data'=>$data], 400);
            }
        }
        catch (\Exception $e)
        {
			return response()->json(['status' => 'ERROR', 'message' => $e->getMessage()], 400);
        }
	}
	
	
	
	
	protected function ptype($q)
	{
	    $numeric = ['no','NUMBER','number','mobile','phone','NO','MOBILE','phone','Mobile','Phone','Number'];
	    $date = ['date','DATE','Date'];
	    
	    foreach ($numeric as $substring) {
            if (Str::contains($q, $substring)) {
                return 'number';
                break;
            }
        }
        foreach ($date as $substring) {
            if (Str::contains($q, $substring)) {
                return 'date';
                break;
            }
        }
        return 'text';
	}
	
	
	
	public function addfundBankList()
	{
	    $banks = DB::table('bank')->get();
		$data = array(
			'addfundBanks' => $banks
		);
		return response()->json(['status' => 'SUCCESS', 'error' => false, 'message' => 'Accounts fetched successfully!!', 'data' => $data], 200);
	}
	# AEPS section
	public function checkKyc(Request $res)
	{
	    $uid = $res->uid;
	    #check kyc is done or not
	    $checkRow = Aeps::where('uid',$uid)->first();
	    if($checkRow == "" || $checkRow == null)
	    {
	        $kycStatus = 'NO';
	        $tfa = false;
	        $aptfa = false;
	    }
	    elseif ($checkRow->status == 1 || $checkRow->status == 2) {
	        $kycStatus = 'PENDING';
	        $tfa = false;
	        $aptfa = false;
	    }
	    else
	    {
	        $kycStatus = 'YES';
	        $tfaDate = Carbon::parse($checkRow->tfadate)->toDateString();
            $current = date('Y-m-d', strtotime(Carbon::now()));
            if($current != $tfaDate)
            $tfa = false;
            else
            $tfa = true;
            #check for Aadhar pay TFA
            $aptfaDate = Carbon::parse($checkRow->aptfa)->toDateString();
            if($current != $aptfaDate)
            $aptfa = false;
            else
            $aptfa = true;
	    }
	    return response()->json(['status'=>'SUCCESS','message'=>'Aeps status fetched successfully','aepsStatus'=>$kycStatus,'tfa'=>$tfa,'aptfa'=>$aptfa,'error'=>false]);
	    
	}
    public function aepskyc(Request $res)
    {
        $user = $res->user();
        $uid =  $user->id;
        $validator = Validator::make($res->all(), [
                'name' => 'required|string',
                'aadhar' => 'required|numeric:digits:12',
                'email' => 'required|email',
                'pincode' => 'required|numeric|digits:6',
                'address' => 'required|string',
                'pan' => 'required|string',
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
            return response()->json(['status'=>'ERROR','data' => $errors], 400);
        }
       
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
        return response()->json(['status'=>'ERROR','message'=>'Already completed the kyc please contact to admin for further process!!'],208);
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
                'request' => json_encode($sendingdata),
                );
            DB::table('aepskyc')->insert($insertData);
            return response()->json(['status'=>'SUCCESS','message'=>'Your outlet ID has been generated go for further procedures!!', 'key' => $sta]);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>$response->msg],400);
        }
		
        
    }
    public function aepsSendotp(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'lat' => 'required',
			'log' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $user = $res->user();
        $uid = $user->id;
        $aepsRow = Aeps::where('uid',$uid)->first();
        $outlet = $aepsRow->outlet;
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
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'error'=>false]);
        }
        else
        {
            DB::table('aepskyc')->where('uid', $uid)->delete();
            if(isset($response->response))
            return response()->json(['status'=>'ERROR','message'=>$response->response]);
            else
            return response()->json(['status'=>'ERROR','message'=>$response->msg],500);
        }
    }
    public function aepsVerifyOtp(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'otp' => 'required|numeric'
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $user = $res->user();
        $uid = $user->id;
        $aepsRow = Aeps::where('uid',$uid)->first();
        $outlet = $aepsRow->outlet;
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
            $upData = array(
                'status' => 2,
                'response' => json_encode($response)
                );
            DB::table('aepskyc')->where('uid',$uid)->update($upData);
            return response()->json(['status'=>'SUCCESS','message'=>'OTP Verified successfully go for further procedure','error'=>false]);
        }
        else
        {
            $upData = array(
                'status' => 1,
                'response' => json_encode($response)
                );
            DB::table('aepskyc')->where('uid',$uid)->update($upData);
            return response()->json(['status'=>'ERROR','message'=>$response->response,'error'=>false],400);
        }
    }
    public function verifyFinger(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'dsrno' => 'required',
			'pidType' => 'required',
			'pidData' => 'required',
			'ci' => 'required',
			'dc' => 'required',
			'dpId' => 'required',
			'errorCode' => 'required',
			'fCount' => 'required',
			'fType' => 'required',
			'hmac' => 'required',
			'mc' => 'required',
			'mi' => 'required',
			'nmPoints' => 'required',
			'qScore' => 'required',
			'rdsId' => 'required',
			'rdsVer' => 'required',
			'sessionKey' => 'required',
			'errorInfo' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $user = $res->user();
        $uid = $user->id;
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
        return response()->json(['status'=>'SUCCESS','type'=>'kyc','message'=>$response->response,'error'=>false]);
        }
        else
        {
            $upData = array(
                'status' => 1,
                'response' => json_encode($response)
                );
            $this->updateData('aepskyc','uid',$uid,$upData);
            return response()->json(['status'=>'ERROR','message'=>isset($response->response)?$response->response:$response->msg,'error'=>false],412);
        }
    }
    public function tfaVerification(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'dsrno' => 'required',
			'pidType' => 'required',
			'pidData' => 'required',
			'ci' => 'required',
			'dc' => 'required',
			'dpId' => 'required',
			'errorCode' => 'required',
			'fCount' => 'required',
			'fType' => 'required',
			'hmac' => 'required',
			'mc' => 'required',
			'mi' => 'required',
			'nmPoints' => 'required',
			'qScore' => 'required',
			'rdsId' => 'required',
			'rdsVer' => 'required',
			'sessionKey' => 'required',
			'errorInfo' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $user = $res->user();
        $uid = $user->id;
        $wallet = DB::table('users')->where('id',$uid)->first()->wallet;
        if($wallet < 1){
            return response()->json(['status'=>'ERROR','message'=>'Minimum 1 rupee has been in your wallet to do 2fa'],401);
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
            'errorinfo' => $res->errorInfo,
            );
        /*DB::table('test')->insert(['data'=>json_encode($sendingdata)]);*/
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),['api-key' => $api_key]);
        /*DB::table('test')->insert(['data'=>$response]);*/
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
        return response()->json(['status'=>'SUCCESS','type'=>'kyc','message'=>'Two-factor authentication Verified successfully','error'=>false]);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false],400);
        }
    }
    public function apTfaVerification(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'dsrno' => 'required',
			'pidType' => 'required',
			'pidData' => 'required',
			'ci' => 'required',
			'dc' => 'required',
			'dpId' => 'required',
			'errorCode' => 'required',
			'fCount' => 'required',
			'fType' => 'required',
			'hmac' => 'required',
			'mc' => 'required',
			'mi' => 'required',
			'nmPoints' => 'required',
			'qScore' => 'required',
			'rdsId' => 'required',
			'rdsVer' => 'required',
			'sessionKey' => 'required',
			'errorInfo' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $user = $res->user();
        $uid = $user->id;
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
            'service_type' => 'AP'
            );
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),['api-key' => $api_key]);
        DB::table('test')->insert(['data'=>$response]);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $upData = array(
                'aptfa' => date('Y-m-d H:i:s', strtotime(Carbon::now())),
                'aptfaresponse' => json_encode($response)
                );
            $this->updateData('aepskyc','uid',$uid,$upData);
        return response()->json(['status'=>'SUCCESS','type'=>'kyc','message'=>'AP Two-factor authentication Verified successfully','error'=>false]);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false],400);
        }
    }
    public function balanceEnquiry(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'dsrno' => 'required',
			'pidType' => 'required',
			'pidData' => 'required',
			'ci' => 'required',
			'dc' => 'required',
			'dpId' => 'required',
			'errorCode' => 'required',
			'fCount' => 'required',
			'fType' => 'required',
			'hmac' => 'required',
			'mc' => 'required',
			'mi' => 'required',
			'nmPoints' => 'required',
			'qScore' => 'required',
			'rdsId' => 'required',
			'rdsVer' => 'required',
			'sessionKey' => 'required',
			'errorInfo' => 'required',
			'mobile' => 'required|numeric|digits:10',
			'aadhar' => 'required|numeric|digits:12',
			'bank' => 'required|numeric',
			'lat' => 'required',
			'log' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $user = $res->user();
        $uid = $user->id;
        $aadhar = $res->aadhar;
        $mobile = $res->mobile;
        $bank = $res->bank;
        $check = $this->checkservice('aeps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error' => false],503);
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
            );
        $response = $this->callApi($url,'POST',$sendingdata,'');
        // return $response;
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
            $notificationData = array("title" => 'Balance Enquiry', "message" => $response->msg, "user_id" => $user->username,  "notify_type" => "transaction_success","transactionID"=>$response->txnid);
            $token = App::where('uid',$uid)->select('device_token')->first()->device_token;
            $this->pushAndroid($token, $notificationData);
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            $insertData['txnamount'] = (double)0;
            $insertData['ministatement'] = [];
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data' => ['receiptData'=>$insertData]]);
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
            $insertData['txnamount'] = (double)0;
            $insertData['ministatement'] = [];
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data' => ['receiptData'=>$insertData]]);
        }
    }
    public function miniStatement(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'dsrno' => 'required',
			'pidType' => 'required',
			'pidData' => 'required',
			'ci' => 'required',
			'dc' => 'required',
			'dpId' => 'required',
			'errorCode' => 'required',
			'fCount' => 'required',
			'fType' => 'required',
			'hmac' => 'required',
			'mc' => 'required',
			'mi' => 'required',
			'nmPoints' => 'required',
			'qScore' => 'required',
			'rdsId' => 'required',
			'rdsVer' => 'required',
			'sessionKey' => 'required',
			'errorInfo' => 'required',
			'mobile' => 'required|numeric|digits:10',
			'aadhar' => 'required|numeric|digits:12',
			'bank' => 'required|numeric',
			'lat' => 'required',
			'log' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $user = $res->user();
        $uid = $user->id;
        $aadhar = $res->aadhar;
        $mobile = $res->mobile;
        $bank = $res->bank;
        $check = $this->checkservice('aeps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $commissionRow = DB::table('mscom')->where('package',$user->package)->first();
        if($commissionRow == "" || $commissionRow == null)
        return response()->json(['status'=>'ERROR','message'=>'Commission is not set for you please contact to admin!','error'=>false],503);
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
        // DB::table('test')->insert(['data'=>$response]);
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
            $insertData['ministatement'] = $response->mini_statement != null? $response->mini_statement:[];
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            $insertData['txnamount'] = (double)0;
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data' => ['receiptData'=>$insertData]]);
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
            $insertData['ministatement'] = isset($response->mini_statement)?$response->mini_statement:[];
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            $insertData['txnamount'] = (double)0;
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data' => ['receiptData'=>$insertData]]);
        }
      }
    public function cashWithdrawal(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'dsrno' => 'required',
			'pidType' => 'required',
			'pidData' => 'required',
			'ci' => 'required',
			'dc' => 'required',
			'dpId' => 'required',
			'errorCode' => 'required',
			'fCount' => 'required',
			'fType' => 'required',
			'hmac' => 'required',
			'mc' => 'required',
			'mi' => 'required',
			'nmPoints' => 'required',
			'qScore' => 'required',
			'rdsId' => 'required',
			'rdsVer' => 'required',
			'sessionKey' => 'required',
			'errorInfo' => 'required',
			'mobile' => 'required|numeric|digits:10',
			'aadhar' => 'required|numeric|digits:12',
			'bank' => 'required|numeric',
			'amount' => 'required|numeric|gt:0',
			'lat' => 'required',
			'log' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$user = $res->user();
		$uid = $user->id;
		$aadhar = $res->aadhar;
        $mobile = $res->mobile;
        $bank = $res->bank;
        $amount = $res->amount;
        $check = $this->checkservice('aeps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $commission = $this->checkcharge($amount,'icaepscomission',$user->package);
        if($commission != true)
        return response()->json(['status'=>'ERROR','message'=>'Commissions are not set for you please contact to admin!','error'=>false],503);
        $outlet = DB::table('aepskyc')->where('uid',$uid)->first()->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partner.ecuzen.in/new/api/aeps/cash_withdrawal";
         $txnid="CW".strtoupper(uniqid());
        $sendingdata = array(
            'lat' => $res->lat,
            'log' => $res->log,
            'api_key' => $api_key,
            'outlet_id' => $outlet,
             'txnid' => $txnid,
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
        // $response = '{"status":"SUCCESS","msg":"Request Completed","bank_ref":"320912728953","txnamount":"1550","txnid":"CW9785212695","Remaining_balance":31.410000000000000142108547152020037174224853515625,"uidai":"","message":"Request Completed","rrn":"320912728953"}';
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
            $this->distributeCommission($uid,'CW-COMMISSION','adhaarpaycharge',$response->txnamount,$response->txnid);
            $insertData['bank'] = DB::table('banks')->where('id',$insertData['bank'])->first()->name;
            $insertData['date'] = Carbon::now()->format('Y-m-d H:i:s');
            $insertData['support'] = DB::table('settings')->where('name','cemail')->first()->value;
            $insertData['ministatement'] = [];
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data' => ['receiptData'=>$insertData]]);
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
            $insertData['ministatement'] = [];
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data' => ['receiptData'=>$insertData]]);
        }
    }
    public function aadharPay(Request $res)
    {
        
        $validator = Validator::make($res->all(), [
			'dsrno' => 'required',
			'pidType' => 'required',
			'pidData' => 'required',
			'ci' => 'required',
			'dc' => 'required',
			'dpId' => 'required',
			'errorCode' => 'required',
			'fCount' => 'required',
			'fType' => 'required',
			'hmac' => 'required',
			'mc' => 'required',
			'mi' => 'required',
			'nmPoints' => 'required',
			'qScore' => 'required',
			'rdsId' => 'required',
			'rdsVer' => 'required',
			'sessionKey' => 'required',
			'errorInfo' => 'required',
			'mobile' => 'required|numeric|digits:10',
			'aadhar' => 'required|numeric|digits:12',
			'bank' => 'required|numeric',
			'amount' => 'required|numeric|gt:0',
			'lat' => 'required',
			'log' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$user = $res->user();
		$uid = $user->id;
        $aadhar = $res->aadhar;
        $mobile = $res->mobile;
        $bank = $res->bank;
        $amount = $res->amount;
        $check = $this->checkservice('aeps',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $charge = $this->checkcharge($amount,'icapcharge',$user->package);
        if($charge != true)
        return response()->json(['status'=>'ERROR','message'=>'Charges are not set for you please contact to admin!','error'=>false],503);
        $outlet = DB::table('aepskyc')->where('uid',$uid)->first()->outlet;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partner.ecuzen.in/iciciaeps/aadharpay";
          $txnid="AP".strtoupper(uniqid());
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
        // $response = '{"status":"FAILED","msg":"Insufficient fund","bank_ref":"320913598751","txnid":"AP4942156996"}';
        #success response
        // $response = '{"status":"SUCCESS","msg":"Request Completed","bank_ref":"320913597136","txnamount":"1000","txnid":"AP1708924550","Remaining_balance":2379.40999999999985448084771633148193359375,"uidai":"XXXXXXXX9387"}';
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
            $insertData['ministatement'] = [];
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data' => ['receiptData'=>$insertData]]);
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
            $insertData['ministatement'] = [];
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data' => ['receiptData'=>$insertData]]);
        }
    }
    #Recharge
    public function getOperatorRecharge($type)
    {
        $blockedUsers = ['mobile', 'dth'];
         if (!in_array($type, $blockedUsers)) {
             return abort(404);
        }
        if($type == 'mobile')
        {
            $operators = DB::table('rechargeop')->where('type',1)->select('id','name','code','type','appimg as image')->get();
        }
        else
        {
            $operators = DB::table('rechargeop')->where('type',0)->select('id','name','code','type','appimg as image')->get();
        }
        $data = array('operatorList'=>$operators);
        return response()->json(['status'=>'SUCCESS','message'=>'Operator fetched successfully','data'=>$data,'error'=>false]);
    }
    public function fetchOperator(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'mobile' => 'required|numeric|digits:10'
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $mobile = $res->mobile;
        $url ="https://digitalapiproxy.paytm.com/v1/mobile/getopcirclebyrange?channel=web&version=2&number={$mobile}&child_site_id=1&site_id=1&locale=en-in"; 
        $response = $this->callApi($url,'GET','','');
        $resData = json_decode($response);
        if(!isset($resData->postpaid)){
            return response()->json(['status'=>'ERROR','message'=>'Invalid number!!','error'=>false]);
        }
        $postpaid = $resData->postpaid;
        if($postpaid == 'true')
        return response()->json(['status'=>'ERROR','message'=>'You entered is a postpaid number!!','error'=>false],412);
        $operator =  strtoupper($resData->Operator);
        if($operator == 'VODAFONE IDEA')
        $operator = 'VI';
        #get data by op name
        $data = DB::table('rechargeop')->where('name', 'like', '%' . $operator . '%')->get();
        $id = $data->pluck('code');
        $plans = $this->getPlans($mobile);
        $plans = json_decode($plans);
        $allPlans = array();
        $fKey = array();
        $i=0;
        if($plans->status == 'SUCCESS')
        {
            foreach ($plans->data as $dat)
            { 
                $allPlans[] = ['catId'=>$i,'catArray'=>$dat->plans??[]];
                $fKey[] = ['opCatName'=>$dat->group_name,'opCatId'=>$i];
                $i++;
            }
        }
        $logo = $data->pluck('image');
        return response()->json(['status'=>'SUCCESS','id'=>$id[0],'message'=>$plans->msg,'operator'=>$resData->Operator,'circle'=>$resData->Circle,'data'=>['allPlans'=>$allPlans,'planTitle'=>$fKey]]); 
    }
    public function fetchPlans(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'type' => 'required|string',
			'plans' => 'required|string',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $type = $res->type;
        $plans = $res->plans;
        $plans = json_decode($plans);
        foreach ($plans as $key=>$val)
        {
            if($type == $key)
            return $val;
        }
    }
    public function doRecharge(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'mobile' => 'required|numeric|digits:10',
			'operator' => 'required|numeric|exists:rechargeop,code',
			'amount' => 'required|numeric|gt:0',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$user = $res->user();
		$uid = $user->id;
		$mobile = $res->mobile;
        $operator = $res->operator;
        $amount = $res->amount;
        $check = $this->checkservice('recharge',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],402);
        $user = DB::table('users')->where('id',$uid)->first();
        $commission = DB::table('comissionv2')->where('package',$user->package)->where('operator',$operator)->first();
        if($commission == null)
        return response()->json(['status'=>'ERROR','message'=>'Commission are not set for you please contact to admin!'],422);
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
            "txnid" => $tempTxnid
            );
        // return $sendingdata;
        $response = $this->callApi($url,'POST',json_encode($sendingdata),'p');
        // return $response;
        /*$response = '{"status":"SUCCESS","msg":"Transaction Successfull","txnid":"RE879323850N"}';*/
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
                'amount1' => (double)$amount,
                'amount2' => (double)$commission,
                'total' => (double)$amount - (double)$commission,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                );
            $recentTxn = DB::table('rechargetxn')->join('rechargeop','rechargeop.id','=','rechargetxn.operator')->limit(5)->orderBy('rechargetxn.id','desc')->select('rechargetxn.mobile','rechargeop.name as operatorName','rechargetxn.amount','rechargetxn.date','rechargetxn.txnid','rechargetxn.status')->get();
            return response()->json(['status'=>'SUCCESS','message' => $response->msg,'data'=>['rechargeData'=>$data,'recentTxn'=>$recentTxn],'closing'=>$wallet - $amount - $commission],200);
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
        return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false],400);
       }
    }
    #Quick Transfer
    public function getAllBankIfsc()
    {
        $global = DB::table('global_ifsc_list')->select('id','name','ifscGlobal')->orderBy('name','ASC')->get();
		$data = array(
			'globalList' => $global
		);
		return response()->json(['status' => 'SUCCESS', 'error' => false, 'message' => 'Bank and IFSC fetched successfully', 'data' => $data], 200);
    }
    public function fetchQtAccount(Request $res)
    {
        /*return 'hii';*/
        $validator = Validator::make($res->all(), [
			'mobile' => 'required|numeric|digits:10',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$mobile = $res->mobile;
        $result = DB::table('qtra_acc')->where('mobile',$mobile)->get();
        if($result->count() > 0)
        {
            foreach ($result as $res)
            {
                if($res->ifsc != "" || $res->ifsc != null)
                {
                    $global = DB::table('global_ifsc_list')->select('name','id')->where('ifscGlobal','like','%'.substr($res->ifsc,0,4).'%')->first();
                    $res->bid = $global->id;
                    $res->bname = $global->name;
                }
            }
            return response()->json(['status'=>'SUCCESS','message'=>'Some accounts fetched','data'=> ['qtAccounts'=>$result],'error'=>false],200);
        }
        else
        { 
            return response()->json(['status'=>'SUCCESS','message'=>'No accounts are associated with this number','data'=> ['qtAccounts'=>[]],'error'=>false]);
        }
    }
    public function initiateQtTransaction(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'amount' => 'required|numeric|gt:0',
                'account' => 'required|numeric|min:10',
                'ifsc' => 'required|string|min:10',
                'mobile' => 'required|numeric|min:10',
                'name' => 'required|string'
            ]);
            if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
        $user = $res->user();
        $uid = $user->id;
        $check = $this->checkservice('qtransfer',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $amount = $res->amount;
        $name = $res->name;
        $account = $res->account;
        $ifsc = $res->ifsc;
        $mobile = $res->mobile;
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!','error'=>false],402);
        $charge = $this->checkcharge($amount,'qtransfer',$user->package);
        if($charge != true)
        return response()->json(['status'=>'ERROR','message'=>'Charges are not set for you please contact to admin!','error'=>false],412);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction','error'=>false],402);
        $maxlimit = $this->checkmaxlimit('qtransfer',$user->package);
        if($maxlimit != true)
        return response()->json(['status'=>'ERROR','message'=>'Unable to get your maximum Transaction limit please contact to admin!!','error'=>false],422);
        if($amount > $maxlimit)
        return response()->json(['status'=>'ERROR','message'=>'Your maximum transaction limit is '.$maxlimit,'error'=>false],422);
        $data = array(
            "amount" => $amount,
            'account' => $account,
            'ifsc' => $ifsc,
            'mobile' => $mobile,
            'name' => $name,
            );
        if($this->txnvia() == 'otp')
        { 
            $otp = rand(100000,999999);
            $otp = '123456';
            
            $sms_api = DB::table('settings')->where('name','sms_api')->first()->value;
            $senderid = "ECZSPL";
            $message = "Welcome To B2B Software Dear " . $user->name . " Your OTP For Amount " . $amount . " Is " . $otp . "	";
            $link = "https://india1sms.com/api/smsg/sendsms?APIKey=".urlencode($sms_api)."&senderid=".urlencode($senderid)."&number=".urlencode($user->phone)."&message=".urlencode($message)."";
            // $res = $this->callApi($link,'POST','','');
            // $res = json_decode($res);
            // if($res->status != 'SUCCESS')
            // return response()->json(['status'=>$res->status,'message'=>$res->message]);
            $message = 'OTP send to your register mobile number';
        }
        else
        {
            $otp = $user->pin;
            $message = 'Please enter your PIN to do the transaction';
        }
        $txnKey = uniqid();
        $insertData = array('data'=>json_encode($data),'otp'=>$otp,'txn_key'=>$txnKey);
        DB::table('qtra_otp')->insert($insertData);
        return response()->json(['status'=>'SUCCESS','message'=>$message,'error'=>false,'txn_key'=>$txnKey,'mode'=>$this->txnvia()]);
    }
    public function qtDoTxn(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'otp' => 'required|numeric',
                'txn_key' => 'required|string',
            ]);
            if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$txnKey = $res->txn_key;
		$getTxnRow = DB::table('qtra_otp')->where('txn_key',$txnKey)->first();
		if($getTxnRow == "" || $getTxnRow == null)
		return response()->json(['status'=>'ERROR','message'=>'Invalid transaction','error'=>false],403);
        $otp = $res->otp;
        $txnData = json_decode($getTxnRow->data);
        if($getTxnRow->otp != $otp)
        return response()->json(['status'=>'ERROR','message'=>'Invalid OTP','error'=>false],401);
        $user = $res->user();
        $uid = $user->id;
        $check = $this->checkservice('qtransfer',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $amount = $txnData->amount;
        $account = $txnData->account;
        $ifsc = $txnData->ifsc;
        $name = $txnData->name;
        $mobile = $txnData->mobile;
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!','error'=>false],402);
        $user = DB::table('users')->where('id',$uid)->first();
        $charge = $this->checkcharge($amount,'qtransfer',$user->package);
        if($charge != true)
        return response()->json(['status'=>'ERROR','message'=>'Charges are not set for you please contact to admin!','error'=>false],412);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction','error'=>false],402);
        
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
        DB::table('qtra_otp')->where('txn_key',$txnKey)->delete();
        // $url = "https://partners.ecuzen.in/api/qt/dotxn";
        $url = "https://partner.ecuzen.in/new/api/qtransfer/dotxn";
        $callbackUrl = env('APP_URL').'/api/quick-transfer-callback';
        $headers = array('api-key' => $api_key,'callback-url'=>$callbackUrl);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingData),$headers);
        #for dummy response
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
                    'rrn' => $TxnDetails->rrn,
                    'status' => $TxnDetails->status,
                    'item1' => 'Quick Transfer',
                    'item2' => 'Q-Transfer charge',
                    'amount1' => (double)$TxnDetails->amount,
                    'amount2' => (double)$charge,
                    'total' => (double)$TxnDetails->amount+(double)$charge,
                    'support' => DB::table('settings')->where('name','cemail')->first()->value,
                    );
                $recentTxn = DB::table('qtransfertxn')->where('uid',$uid)->limit(5)->get();
                return response()->json(['status'=>'SUCCESS','data'=>['qtData'=>$data,'recentQtTxn'=>$recentTxn],'message'=>'Transaction Successfull','closing'=>round($wallet - $amount+$charge,2)],200);
            }
            else
            {
                $insdata = array(
                    'uid' => $uid,
                    'txnid' => $TxnDetails->txnid??'NA',
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
                $recentTxn = DB::table('qtransfertxn')->where('uid',$uid)->limit(5)->get();
                return response()->json(['status'=>'ERROR','data'=>['recentQtTxn'=>$recentTxn],'message'=>$TxnDetails->msg??$response->msg],406);
            }
        }
        else
        {
            $insdata = array(
                    'uid' => $uid,
                    'txnid' => $TxnDetails->txnid??'NA',
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
                $recentTxn = DB::table('qtransfertxn')->where('uid',$uid)->limit(5)->get();
                return response()->json(['status'=>'ERROR','data'=>['recentQtTxn'=>$recentTxn],'message'=>$TxnDetails->msg??$response->msg],406);
        }
        
    }
    #payout 
    public function getPayoutAccounts(Request $res)
    {
        $user = $res->user();
        $uid = $user->id;
        $accounts = DB::table('payoutaccount')->where('uid',$uid)->get();
        if(count($accounts) == 0)
        return response()->json(['status'=>'SUCCESS','message'=>'No accounts found!!','data'=>['payoutAccounts'=>[]],'error'=>false]);
        return response()->json(['status'=>'SUCCESS','message'=>'Accounts Fetched successfully','data'=>['payoutAccounts'=>$accounts],'error'=>false]);
    }
    public function addPayoutAccount(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'name' => 'required|string',
            'account' => 'required|numeric|digits_between:10,15',
            'ifsc' => 'required|string|min:10',
            'passbook' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		#check for account 
		$uid = $res->uid;
        $account = $res->account;
        $check = DB::table('payoutaccount')->where('account',$account)->count();
        if($check > 0)
        return response()->json(['status'=>'ERROR','message'=>'Account already added!!','error'=>false],208);
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
            "uid" => $uid,
            "name" => $name,
            "account" => $account,
            "ifsc" => $ifsc,
            "passbook" => $imagePath,
            "status" => 'PENDING',
            );
        $res = $this->insertdata('payoutaccount',$data);
        if($res != true)
        return response()->json(['status'=>'ERROR','message'=>'Unknown error occured!','error'=>false],500);
        $accounts = DB::table('payoutaccount')->where('uid',$uid)->get();
        return response()->json(['status'=>'SUCCESS','message'=>'Account added successfully!! Please wait untill admin approve your account!!','data'=>['payoutAccounts'=>$accounts],'error'=>false],200);
    }
    public function deletePayoutAccount(Request $res)
    {
        $id = $res->id;
        $getRow = DB::table('payoutaccount')->where('id',$id)->first();
        if($getRow == "" || $getRow == null)
        return response()->json(['status'=>'ERROR','message'=>'Invaid id!!','error'=>false],404);
        $uid = $res->uid;
        if($getRow->uid != $uid)
        return response()->json(['status'=>'ERROR','message'=>'You dont have the previlege to delete this account!','error'=>false],401);
        #time to delete existing image
        $imageUrl = $getRow->passbook;
        $filename = basename($imageUrl);
        $filePath = '/storage/images/' . $filename;
        if (Storage::disk('public')->exists($filePath))
        Storage::disk('public')->delete($filePath);
        $res = DB::table('payoutaccount')->where('id',$id)->delete();
        if($res)
        return response()->json(['status'=>'SUCCESS','message'=>'Account deleted successfully!! Please refresh to see latest data','error'=>false],200);
        return response()->json(['status'=>'ERROR','Database error','error'=>false],500);
    }
    public function payoutInitiateTransaction(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'mode' => ['required',
            Rule::in(['IMPS', 'NEFT']),
            ],
            'mobile' => 'required|numeric|digits:10',
            'bid' => 'required|numeric|gt:0|exists:payoutaccount,id',
            'amount' => 'required|numeric|gt:0',
            ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$uid = $res->uid;
        $check = $this->checkservice('payout',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $bid = $res->bid;
        $mode = $res->mode;
        $amount = $res->amount;
        $mobile = $res->mobile;
        #check account is approved or not
        $accCheck = DB::table('payoutaccount')->where('id',$bid)->first()->status;
        if($accCheck != 'APPROVED')
        return response()->json(['status'=>'ERROR','message'=>'The account you are using is not approved','error'=>false],412);
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!','error'=>false],402);
        $user = $res->user();
        $table = $mode == 'IMPS'?'payoutchargeimps':'payoutchargeneft';
        $charge = $this->checkcharge($amount,$table,$user->package);
        if($charge != true)
        return response()->json(['status'=>'ERROR','message'=>'Charges are not set for you please contact to admin!','error'=>false],422);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction','error'=>false],402);
         $maxlimit = $this->checkmaxlimit($table,$user->package);
        if($maxlimit != true)
        return response()->json(['status'=>'ERROR','message'=>'Unable to get your maximum Transaction limit please contact to admin!!','error'=>false],422);
        if($amount > $maxlimit)
        return response()->json(['status'=>'ERROR','message'=>'Your maximum transaction limit is '.$maxlimit,'error'=>false],422);
        if($this->txnvia() == 'otp')
        { 
            $otp = rand(100000,999999);
            $otp = '123456';
            
            $sms_api = DB::table('settings')->where('name','sms_api')->first()->value;
            $senderid = "ECZSPL";
            $message = "Welcome To B2B Software Dear " . $user->name . " Your OTP For Amount " . $amount . " Is " . $otp . "	";
            $link = "https://india1sms.com/api/smsg/sendsms?APIKey=".urlencode($sms_api)."&senderid=".urlencode($senderid)."&number=".urlencode($user->phone)."&message=".urlencode($message)."";
            // $res = $this->callApi($link,'POST','','');
            // $res = json_decode($res);
            // if($res->status != 'SUCCESS')
            // return response()->json(['status'=>$res->status,'message'=>$res->message]);
            $message = 'OTP send to your register mobile number';
        }
        else
        {
            $otp = $user->pin;
            $message = 'Please enter your PIN to do the transaction';
        }
        $txnKey = uniqid();
        $insertData = array(
            'phone' => $mobile,
            'data' => json_encode(['bid'=>$bid,'amount'=>$amount]),
            'otp' => $otp,
            'mode' => $mode,
            'txn_key' => $txnKey,
            'otp_type' => $this->txnvia()
            );
        DB::table('payout_otp')->insert($insertData);
        return response()->json(['status'=>'SUCCESS','message'=>$message,'error'=>false,'txn_key'=>$txnKey,'mode'=>$this->txnvia()]);
    }
    public function payoutDoTransaction(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'otp' => 'required|numeric',
            'txn_key' => 'required|string|exists:payout_otp,txn_key',
        ]);
        if ($validator->fails()) {
		    return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$txnKey = $res->txn_key;
		$getTxnRow = DB::table('payout_otp')->where('txn_key',$txnKey)->first();
        $otp = $res->otp;
        if($getTxnRow->otp != $otp)
        return response()->json(['status'=>'ERROR','message'=>'Invalid '.strtoupper($getTxnRow->otp_type)],400);
        $user = $res->user();
        $uid = $user->id;
        $txnData = json_decode($getTxnRow->data);
        $amount = $txnData->amount;
        $bid = $txnData->bid;
        $phone = $getTxnRow->phone;
        $mode = $getTxnRow->mode;
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!','error'=>false],402);
        $check = $this->checkservice('payout',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $table = $mode == 'IMPS'?'payoutchargeimps':'payoutchargeneft';
        $charge = $this->checkcharge($amount,$table,$user->package);
        if($charge != true)
        return response()->json(['status'=>'ERROR','message'=>'Charges are not set for you please contact to admin!','error'=>false],422);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction','error'=>false],402);
         $maxlimit = $this->checkmaxlimit($table,$user->package);
        if($maxlimit != true)
        return response()->json(['status'=>'ERROR','message'=>'Unable to get your maximum Transaction limit please contact to admin!!','error'=>false],422);
        if($amount > $maxlimit)
        return response()->json(['status'=>'ERROR','message'=>'Your maximum transaction limit is '.$maxlimit,'error'=>false],422);
        $accountDetails = DB::table('payoutaccount')->where('id',$bid)->first();
        if($accountDetails == "" || $accountDetails == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid account details'],403);
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
        $this->debitEntry($uid,'Payout',$tempTxnid,$amount);
        $this->debitEntry($uid,'Payout-charge',$tempTxnid,$charge);
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
        DB::table('payout_otp')->where('txn_key',$txnKey)->delete();
        $sendingdata = json_encode($sendingdata);
        $url = "https://partner.ecuzen.in/new/api/payout/dopayout";
        $response = $this->callApi($url,'POST',$sendingdata,[]);
       /* $response = '{"status":"SUCCESS","txnid":"PYOT686062722598898","amount":1,"rrn":"315812798064","msg":"Transaction SUCCESS","account":"555710510003414","ifsc":"BKID0005557","name":"Manas","mode":"IMPS"}';*/
        $response = json_decode($response,true);
        $status =  $response['status'];
        if($status=="SUCCESS" || $status=="success" || $status=="PENDING" || $status=="IN_PROCESS" || $status == "accepted" || $status == "ACCEPTED")
        {
            $insdata = array(
                'uid' => $uid,
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
                'amount1' => (double)$amount,
                'amount2' => (double)$charge,
                'total' => (double)$amount+$charge,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                'mode' => $response['mode'],
                'rrn' => $response['rrn'],
                );
            return response()->json(['status'=>'SUCCESS','data'=>['payoutData'=>$data],'message'=>'Transaction Successfull','closing'=>round($wallet - $amount+$charge,2)],200);
        }
        else
        {
            $insdata = array(
                'uid' => $uid,
                'txnid' => $response['txnid']??$tempTxnid,
                'account' => $account,
                'ifsc' => $ifsc,
                'bname' => $name,
                'amount' =>$amount,
                'status' => $status,
                'message' => $response['msg'],
                'response' => json_encode($response),
                'rrn' => isset($response['rrn']) ? $response['rrn'] : "NA",
                'mode' => isset($response['mode']) ? $response['mode'] : "NA",
                );
            // $this->insertdata('payouttxn',$insdata);
            $this->updateData('payouttxn','txnid',$tempTxnid,$insdata);
            $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$response['txnid']??$tempTxnid]);
            $this->creditEntry($uid,'PAYOUT-REFUND',$response['txnid']??$tempTxnid,$amount+$charge);
            return response()->json(['status'=>'ERROR','message'=>$response['msg']],406);
            
        }
    }
    #wallet to wallet
    public function fetchUserByNumber(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'phone' => 'required|numeric|digits:10',
        ]);
        if ($validator->fails()) {
		    return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$uid = $res->uid;
		$mobile = $res->phone;
		$self = User::find($uid);
        if($self->phone == $mobile)
        return response()->json(['status'=>'ERROR','message'=>'Cant send money to your self!!','error'=>false],412);
        $checkUser = User::where('phone',$mobile)->first();
        if($checkUser == "" || $checkUser == null)
        return response()->json(['status'=>'ERROR','message'=>'User not associated with this number','error'=>false],422);
        $returnData = array(
            'username' => $checkUser->username,
            'name' => $checkUser->name,
            );
        return response()->json(['status'=>'SUCCESS','message'=>'User found successfully!!','data'=>['WalletFetchUser'=>$returnData],'error'=>false]);
    }
    public function walletTowalletDoTransactions(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'phone' => 'required|numeric|digits:10',
            'amount' => 'required|numeric|gt:0',
        ]);
        if ($validator->fails()) {
		    return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$mobile = $res->phone;
        $amount = $res->amount;
        $user = $res->user();
        $uid = $user->id;
        /*$check = $this->checkservice('walletTowallet',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $charge = $this->checkcharge($amount,'qtransfer',$user->package);
        if($charge != true)
        return response()->json(['status'=>'INFO','message'=>'Charges are not set for you please contact to admin!','error'=>false],422);
        if($user->wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction','error'=>false],402);*/
        if($amount > $user->wallet)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet','error'=>false],402);
        if($mobile == $user->phone)
        return response()->json(['status'=>'ERROR','message'=>'Cant send money to your self!!','error'=>false],412);
        $receiverUser = User::where('phone',$mobile)->first();
        if($receiverUser == "" || $receiverUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Receiver user not found','error'=>false],412);
        $txnid = "WTW".rand(100000,999999)."ECZ".rand(100,999);
        $insertdata = array(
            'sender' => $user->id,
            'receiver' => $receiverUser->id,
            'txnid' => $txnid,
            'amount' => $amount,
            'type' => 'DEBIT'
            );
        DB::table('wallet_to_wallet')->insert($insertdata);
        $this->creditEntry($receiverUser->id,'WALLET-TRANSFER',$txnid,$amount);
        $this->debitEntry($uid,'WALLET-TRANSFER',$txnid,$amount);
        // $this->debitEntry($uid,'WALLET-TRANSFER-CHARGE',$txnid,$charge);
        $data = array(
            'date' => Carbon::now()->format('Y-m-d H:i:s'),
            'senderName' => $user->name,
            'senderMobile' => $user->phone,
            'receiverName' => $receiverUser->name,
            'receiverMobile' => $receiverUser->phone,
            'txnid' => $txnid,
            'amount' => $amount,
            'status' => 'SUCCESS',
            'support' => DB::table('settings')->where('name','cemail')->first()->value,
            );
        $latestTxns = DB::table('wallet_to_wallet')->where('sender',$uid)->orWhere('receiver',$uid)->orderBy('id', 'desc')->limit(5)->get();
        $txns = array();
        foreach ($latestTxns as $txn)
        {
            if($txn->receiver == $uid)
            $type = 'CREDIT';
            else
            $type = 'DEBIT';
            $sender = User::select('name','phone')->where('id',$txn->sender)->first();
            $receiver = User::select('name','phone')->where('id',$txn->receiver)->first();
            $txnData = array(
                'senderName' => $sender->name,
                'senderPhone' => $sender->phone,
                'receiverName' => $receiver->name,
                'receiverphone' => $receiver->phone,
                'type' => $type,
                'txnid' => $txn->txnid,
                'amount' => $txn->amount,
                'date' => $txn->date,
                );
            $txns[] = $txnData;
        }
        return response()->json(['status'=>'SUCCESS','message'=>'Transaction successfull','data'=>['walletToWalletData'=>$data,'walletToWalletRecentTxn'=>$txns],'error'=>false],200);
    }
    
    
    public function dmtLogin(Request $res)
    {
        $validator = Validator::make($res->all(), [
			'phone' => 'required|numeric|digits:10',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$phone = $res->phone;
		$uid = $res->uid;
		$check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $url = "https://partners.ecuzen.in/api/dmt/remitter_details";
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $headers = array('api-key' => $api_key);
        $sendingdata = ["mobile" => $phone];
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $txnKey = uniqid();
            if(!empty($response->info))
            {
                #check already Login
                $checkAlreadyLogin = DB::table('app_dmt')->where('phone',$phone)->count();
                if($checkAlreadyLogin != 1)
                DB::table('app_dmt')->insert(['phone'=>$phone,'txn_key'=>$txnKey]);
                else
                DB::table('app_dmt')->where('phone',$phone)->update(['txn_key'=>$txnKey]);
                
                
                $limit1 = $response->info->bank1_limit??'0';
                $limit2 = $response->info->bank2_limit??'0';
                $limit3 = $response->info->bank3_limit??'0';
                
                
                $dmtProfileData = array(
                    'remainingLimit'=>$limit1 + $limit2 + $limit3,
                    'name' => $response->info->fname." ".$response->info->lname,
                    'mobile' => $response->info->mobile,
                    );
                return response()->json(['status'=>'SUCCESS','activity'=>'dashboard','message'=>$response->msg,'dmtKey'=>$txnKey,'data'=>['dmtProfileData'=>$dmtProfileData]]);
            }
            else
            {
                $checkAlreadyLogin = DB::table('app_dmt')->where('phone',$phone)->count();
                if($checkAlreadyLogin != 1)
                DB::table('app_dmt')->insert(['phone'=>$phone,'txn_key'=>$txnKey,'reg_key'=>$response->stateresp]);
                else
                DB::table('app_dmt')->where('phone',$phone)->update(['txn_key'=>$txnKey,'reg_key'=>$response->stateresp]);
                return response()->json(['status'=>'SUCCESS','activity'=>'register','message'=>$response->msg,'dmtKey'=>$txnKey]);
            }
        }
        else 
        {
            return response()->json(['status'=>$response->status,'message'=>$response->msg,'error'=>false]);
        }
    }
    public function dmtRegister(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'fname' => 'required|string',
            'lname' => 'required|string',
            'otp' => 'required|numeric',
            'pincode' => 'required|numeric|digits:6',
            'address' => 'required|string',
            'dob' => 'required|date',
            'dmtKey' => 'required|string|exists:app_dmt,txn_key',
        ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$uid = $res->uid;
		$check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        #get user dmt data 
        $getDmtUser = DB::table('app_dmt')->where('txn_key',$res->dmtKey)->first();
        if($getDmtUser == "" || $getDmtUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid dmt key','error'=>false],403);
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partners.ecuzen.in/api/dmt/register";
        $sendingdata = [
            "mobile" => $getDmtUser->phone,
            "stateresp" => $getDmtUser->reg_key,
            "firstname" => $res->fname,
            "lastname" => $res->lname,
            "pincode" => $res->pincode,
            "otp" => $res->otp,
            "dob" => $res->dob,
            "address" => $res->address,
        ];
        $headers = array('api-key' => $api_key);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'error'=>false]);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false],501);
        }
    }
    public function dmtData(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'dmtKey' => 'required|string|exists:app_dmt,txn_key',
        ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$uid = $res->uid;
		$check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        #get user dmt data 
        $getDmtUser = DB::table('app_dmt')->where('txn_key',$res->dmtKey)->first();
        if($getDmtUser == "" || $getDmtUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid dmt key','error'=>false],403);
        $phone = $getDmtUser->phone;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partners.ecuzen.in/api/dmt/fetchbene";
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $headers = array('api-key' => $api_key);
        $sendingdata = ["mobile" => $phone];
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data'=>['dmtData'=>$response->data],'error'=>false]);
        else
        return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false],501);
    }
    public function dmtBankList()
    {
        $banklist = DB::table('dmtbanklist')->select('bid as id','bname as name')->orderBy('bname', 'asc')->get();
        return response()->json(['status'=>'SUCCESS','message'=>'DMT Bank list fetched successfully','data'=>['dmtBankList'=>$banklist]]);
    }
    public function dmtAddAccount(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'name' => 'required|string',
            'account' => 'required|numeric',
            'ifsc' => 'required|string',
            'bid' => 'required|numeric',
            'dob' => 'required|date|before:today',
            'pincode' => 'required|numeric|digits:6',
            'dmtKey' => 'required|string|exists:app_dmt,txn_key',
        ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$uid = $res->uid;
		$check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        #get user dmt data 
        $getDmtUser = DB::table('app_dmt')->where('txn_key',$res->dmtKey)->first();
        if($getDmtUser == "" || $getDmtUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid dmt key','error'=>false],403);
        $phone = $getDmtUser->phone;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partners.ecuzen.in/api/dmt/regben";
        $sendingdata =array(
            "mobile" =>$phone,
            "benename" =>$res->name,
            "bankid" =>$res->bid,
            "accno" =>$res->account,
            "pincode" =>$res->pincode,
            "dob" =>$res->dob,
            "ifsccode" => $res->ifsc,
            );
        $headers = array('api-key' => $api_key);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'error' => false]);
        else
        return response()->json(['status'=>'ERROR','message'=>$response->msg,'error' => false],501);
    }
    public function dmtDeleteAccount(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'dmtKey' => 'required|string|exists:app_dmt,txn_key',
            'beneId' => 'required|numeric'
        ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$uid = $res->uid;
		$check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        #get user dmt data 
        $getDmtUser = DB::table('app_dmt')->where('txn_key',$res->dmtKey)->first();
        if($getDmtUser == "" || $getDmtUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid dmt key','error'=>false],403);
        $phone = $getDmtUser->phone;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = "https://partners.ecuzen.in/api/dmt/delbene";
        $sendingdata = [
            "mobile" => $phone,
            "bene_id" => $res->beneId,
        ];
        $headers = array('api-key' => $api_key);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'error'=>false]);
        else
        return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false],503);
    }
    public function dmtInitiateTransaction(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'dmtKey' => 'required|string|exists:app_dmt,txn_key',
            'beneId' => 'required|numeric',
            'mode' => 'required|string',
            'amount' => 'required|numeric|gt:0',
            'ifsc' => 'required|string',
        ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$uid = $res->uid;
		$user = $res->user();
		$beneId = $res->beneId;
		$mode = $res->mode;
		$amount = $res->amount;
		$ifsc = $res->ifsc;
		$dmtKey = $res->dmtKey;
		$check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        #get user dmt data 
        $getDmtUser = DB::table('app_dmt')->where('txn_key',$dmtKey)->first();
        if($getDmtUser == "" || $getDmtUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid dmt key','error'=>false],403);
        $phone = $getDmtUser->phone;
        $wallet = $this->checkwallet($uid);
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!','error'=>false],402);
        $charge = $this->checkcharge($amount,'dmtcharge',$user->package);
        if($charge != true)
        return response()->json(['status'=>'ERROR','message'=>'Charges are not set for you please contact to admin!','error'=>false],412);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction','error'=>false],402);
        $maxlimit = $this->checkmaxlimit('dmtcharge',$user->package);
        if($maxlimit != true)
        return response()->json(['status'=>'ERROR','message'=>'Unable to get your maximum Transaction limit please contact to admin!!','error'=>false],412);
        if($amount > $maxlimit)
        return response()->json(['status'=>'ERROR','message'=>'Your maximum transaction limit is '.$maxlimit,'error'=>false],412);
        if($this->txnvia() == 'otp')
        { 
            $otp = rand(100000,999999);
            $otp = '123456';
            
            $sms_api = DB::table('settings')->where('name','sms_api')->first()->value;
            $senderid = "ECZSPL";
            $message = "Welcome To B2B Software Dear " . $user->name . " Your OTP For Amount " . $amount . " Is " . $otp . "	";
            $link = "https://india1sms.com/api/smsg/sendsms?APIKey=".urlencode($sms_api)."&senderid=".urlencode($senderid)."&number=".urlencode($user->phone)."&message=".urlencode($message)."";
            // $res = $this->callApi($link,'POST','','');
            // $res = json_decode($res);
            // if($res->status != 'SUCCESS')
            // return response()->json(['status'=>$res->status,'message'=>$res->message]);
            $message = 'OTP send to your register mobile number';
        }
        else
        {
            $otp = $user->pin;
            $message = 'Please enter your PIN to do the transaction';
        }
        $data = array(
            "amount" => $amount,
            'mode' => $mode,
            'bene_id' => $beneId,
            'otp' => $otp,
            'ifsc' => $res->ifsc,
            );
        
        DB::table('app_dmt')->where('txn_key',$dmtKey)->update(['txn_data'=>json_encode($data)]);
        return response()->json(['status'=>'SUCCESS','message'=>$message,'error'=>false,'mode'=>$this->txnvia()]);
    }
    public function dmtDoTransactions(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'dmtKey' => 'required|string|exists:app_dmt,txn_key',
            'otp' => 'required|numeric',
        ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$enotp = $res->otp;
		$getTxnRow = DB::table('app_dmt')->where('txn_key',$res->dmtKey)->first();
		$txnData = json_decode($getTxnRow->txn_data);
		$amount = $txnData->amount;
		$mode = $txnData->mode;
		$beneId = $txnData->bene_id;
		$otp = $txnData->otp;
		$ifsc = $txnData->ifsc;
		$phone = $getTxnRow->phone;
		if($otp != $enotp)
		return response()->json(['status'=>'ERROR','message'=>'Please enter valid otp','error'=>false],403);
		$user = $res->user();
		$uid = $user->id;
		$check = $this->checkservice('dmt',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $wallet = $this->checkwallet($uid);
        // return $wallet;die;
        if($wallet < $amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!','error'=>false],402);
        $charge = $this->checkcharge($amount,'dmtcharge',$user->package);
         if($charge != true)
        return response()->json(['status'=>'ERROR','message'=>'Charges are not set for you please contact to admin!','error'=>false],412);
        if($wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction','error'=>false],402);
        $maxlimit = $this->checkmaxlimit('dmtcharge',$user->package);
        if($maxlimit != true)
        return response()->json(['status'=>'ERROR','message'=>'Unable to get your maximum Transaction limit please contact to admin!!','error'=>false],412);
        if($amount > $maxlimit)
        return response()->json(['status'=>'ERROR','message'=>'Your maximum transaction limit is '.$maxlimit,'error'=>false],412);
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
                'ifsc' => $ifsc,
                );
        $this->insertdata('dmrtxn',$insdata);
        $this->debitEntry($uid,'DMT',$tempTxnid,$amount);
        $this->debitEntry($uid,'DMT-CHARGE',$tempTxnid,$charge);
        DB::table('app_dmt')->where('txn_key',$getTxnRow->txn_key)->update(['txn_data'=>null]);
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $callbackUrl = env('APP_URL').'/api/dmt-callback';
        $sendingdata = array(
            "mobile"=>$phone,
            "bene_id"=>$beneId,
            "amount"=>(double)$amount,
            "mode"=>$mode,
            "ref_key"=>$tempTxnid
            );
        $url = 'https://partners.ecuzen.in/api/dmt/dotxn';
        $headers = array('api-key' => $api_key,'callback-url'=>$callbackUrl);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        #success response
        // $response = '{"status":"SUCCESS","msg":"Transaction is on hold","data":{"status":true,"response_code":1,"ackno":"68164981","referenceid":"DMT426030709312","utr":"0","txn_status":4,"benename":"","remarks":"timeout - ","message":"Transaction is on hold","remitter":"8696921014","account_number":"677105601578","bc_share":"2.5","txn_amount":"100","NPCI_response_code":"500","bank_status":"no","balance":928.1299999999999954525264911353588104248046875,"customercharge":"10.00","gst":"1.53","tds":"0.30","netcommission":"5.67"}}';
        #error response
        // $response = '{"status":"ERROR","msg":"The amount field must be at least 100.","info":{"amount":["The amount field must be at least 100."]}}';
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $responseData = $response->data;
            $updateData = array(
                'txnid' => $responseData->referenceid,
                'account' => $responseData->account_number,
                'bname' => $responseData->benename,
                'status' => $response->status,
                'message' => $response->msg,
                'utr' => $responseData->utr,
                'response' => json_encode($response)
                );
            $this->updateData('dmrtxn','tempid',$tempTxnid,$updateData);
            $this->updateData('wallet','txnid',$tempTxnid,['txnid'=>$responseData->referenceid]);
            $this->distributeCommission($uid,'DMT-Commission','dmtcharge',$amount,$responseData->referenceid);
            $data = array(
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'name' => $responseData->benename,
                'txnid' => $responseData->referenceid,
                'account' => $responseData->account_number,
                'ifsc' => $ifsc,
                'status' => $response->status,
                'item1' => 'DMT',
                'item2' => 'DMT-CHARGE',
                'amount1' => (double)$amount,
                'amount2' => (double)$charge,
                'total' => (double)$amount+$charge,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                'rrn' => $responseData->utr,
                'mode' => $mode,
                );
            return response()->json(['status'=>'SUCCESS','message'=>$response->msg,'data'=>['dmtReceiptData'=>$data],'error'=>false]);
        }
        else
        {
            $responseData = $response->data??null;
            $insdata = array(
                'txnid' => $responseData->referenceid??$tempTxnid,
                'account' => $responseData->account_number??'NA',
                'ifsc' => $ifsc,
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
            return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false],406);
        }
    }
    public function dmtLogout(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'dmtKey' => 'required|string|exists:app_dmt,txn_key',
        ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		DB::table('app_dmt')->where('txn_key',$res->dmtKey)->delete();
		return response()->json(['status'=>'SUCCESS','message'=>'DMT Logout successfully!!','error'=>false]);
    }
    
    
    public function utiCheckStatus(Request $res)
    {
        $uid = $res->uid;
        $user = $res->user();
        #check uti register status
        $coupanCharge = 0;
        $checkStatus = DB::table('psa')->where('uid',$uid)->first();
        if($checkStatus == "" || $checkStatus == null)
        {
            $status = 'NO';
            $psaid = '';
        }
        else if($checkStatus->status == 'PENDING')
        {
            $status = 'PENDING';
            $psaid = '';
        }
        else
        {
            $status = 'YES';
            $psaid = $checkStatus->psaid;
            $coupanCharge  = (double)DB::table('coponcharge')->where('package',$user->package)->first()->amount;
        }
        $kycData = array(
				'supportmail' => Setting::where('name', 'cemail')->first()->value,
				'supportphone' => Setting::where('name', 'cnumber')->first()->value,
			);
        return response()->json(['status'=>'SUCCESS','message'=>'UTI status fetched successfully','utiStatus'=>$status,'psaid' => $psaid,'couponCharge'=>$coupanCharge,'data'=>['utiSupportData'=>$kycData],'error'=>false]);
    }
    public function utiRegister(Request $res)
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
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$uid = $res->uid;
		$check = $this->checkservice('uti',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        #check for already existance 
        $checkStatus = DB::table('psa')->where('uid',$uid)->count();
        if($checkStatus != 0)
        return response()->json(['status'=>'ERROR','message'=>'You already applied for UTI','error'=>false],208);
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $sendingdata= array(
            'api_key' => $api_key,
            'psaid' => "ECZ".$res->phone,
            'mobile' => $res->phone,
            'email' =>$res->email,
            'shop' => $res->slocation,
            'name' => $res->name,
            'pan' => $res->pan,
            'pincode' => $res->pincode,
            'location' => $res->address,
            'aadhaar' => $res->aadhar,
            'state' => $res->state
            );
        $url = 'https://partners.ecuzen.in/api/pan/register';
        $headers = array('api-key' => $api_key);
        $response = $this->callApiwithHeader($url,'POST',json_encode($sendingdata),$headers);
        $response =  json_decode($response);
        if($response->status == 'SUCCESS')
        {
            $sendingdata['uid'] = $uid;
            $sendingdata['adhaar'] = $sendingdata['aadhaar'];
            Arr::forget($sendingdata, 'api_key');
            Arr::forget($sendingdata, 'aadhaar');
            $insData = $sendingdata;
            $insData['status'] = $response->psa_status;
            $res = $this->insertdata('psa',$insData);
            return response()->json(['status'=>'SUCCESS','message'=>'Your application processed successfully your PSAID is :-'.$response->psa_id,'error'=>false]);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false],422);
        }
    }
    public function buyUtiCoupon(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'qty' => 'required|numeric|gt:0',
        ]);
        if ($validator->fails()) {
			return response()->json(['status' => 'ERROR', 'message' => $validator->errors()->first(), 'info' => $validator->errors(),'error'=>false],400);
		}
		$uid = $res->uid;
		$qty = $res->qty;
		$user = $res->user();
		$check = $this->checkservice('uti',$uid);
        if($check->status == 'ERROR')
        return response()->json(['status'=>'ERROR','message'=>$check->message,'error'=>false],503);
        $wallet = $this->checkwallet($uid);
        $coupanCharge  = DB::table('coponcharge')->where('package',$user->package)->first()->amount;
        $totalCharge = $coupanCharge * $qty;
        if($wallet < $coupanCharge)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!','error'=>false],402);
        $psaRow = DB::table('psa')->where('uid',$uid)->first();
        if($psaRow->status != 'APPROVED')
        return response()->json(['status'=>'ERROR','message'=>'Your PSAID is not approved please wait for admin to approve!!','error'=>false],422);
        $psaId = $psaRow->psaid;
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $sendingdata = array(
            'api_key' => $api_key,
            'psa_id' => $psaId,
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
            $data = array(
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'txnid' => $response->txnid,
                'status' => $response->status,
                'item1' => 'Quantity',
                'item2' => 'Total',
                'amount1' => (double)$qty,
                'amount2' => (double)$totalCharge,
                'support' => DB::table('settings')->where('name','cemail')->first()->value,
                );
            return response()->json(['status'=>'SUCCESS','message' => 'UTI Coupon purchased successfully','data' =>['utiReceipt'=>$data]]);
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
            return response()->json(['status'=>'ERROR','message'=>$response->msg,'error'=>false],422);
        }
    }
    public function getAllsupportType(Request $res)
    {
        $data = DB::table('support')->select('id','name')->get();
        $tickets = DB::table('ticket')->where('uid',$res->uid)->select('*')->orderBy('id','desc')/*->except(['uid','department','date'])*/->get();
        return response()->json(['status'=>'SUCCESS','message'=>'Support types fetched successfully','data'=>['supportTypes'=>$data,'supportTickets'=>$tickets??[]],'error'=>false]);
    }
    
      public function registerAllsupportType(Request $res)
    {
        $filteredList = ['aeps','money-transfer','qtransfer','payout','bbps','recharge','uti-coupon','wallet','wallet-to-wallet','AEPS','DMT','Q-Transfer','Payout','BBPS','Recharge','UTI-Coupon','Wallet','Wallet-to-Wallet'];
		$rules = [
			'message' => [],
			'txnid' => [],
			'service' => [],
		];
		$rules['message'][] = 'required';
		$rules['service'][] = 'required';
		$service = $res->service;
        if (in_array($service, $filteredList)) {
		    $rules['txnid'][] = 'required';
		    $rules['txnid'][] = 'unique:ticket,txnid';
		}
		$validator = Validator::make($res->all(), $rules);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return response()->json(['status' => 'ERROR', 'message' => $errors->first(), 'info' => $errors,'error'=>false], 400);
		}
		if($service == 'money-transfer')
		$service = 'DMT';
        $insertData = array(
            'uid' => $res->uid,
            'ticketid' => "TKT".uniqid(),
            'message' =>$res->message,
            'txnid' => $res->txnid??'NA',
            'service' => strtoupper($service),
            'status' => "PENDING",
            ); 
        $this->insertdata('ticket',$insertData);
        return response()->json(['status'=>'SUCCESS','message'=>'Ticket Raised  successfully','error'=>false]);
    }
    public function accountVerify(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'account' => 'required|numeric',
            'ifsc' => 'required|string',
            'mobile' => 'required|numeric|digits:10',
            'lat' => 'required',
            'log' => 'required'
        ]);
        if ($validator->fails()) {
			$errors = $validator->errors();
			return response()->json(['status' => 'ERROR', 'message' => $errors->first(), 'info' => $errors,'error'=>false], 400);
		}
        $uid = $res->uid;
        $user = User::find($uid);
        $username = $user->username;
        $wallet = $this->checkwallet($uid);
        $charge = DB::table('accountVerify')->where('package',$user->package)->first()->amount;
        if($wallet < $charge)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],400);
        $account = $res->account;
        $ifsc = $res->ifsc;
        $phone = $res->mobile;
        $lat = $res->lat;
        $log = $res->log;
        
        $checkacc = DB::table('qtra_acc')->where('account',$account)->where('mobile' , $phone);
        if($checkacc->count() > 0){
             if($checkacc->first()->fetch_acc == 1)
            {
                return response()->json(['status'=>'SUCCESS','message'=>'Your Account Allready Verified!!', 'name' => $checkacc->first()->bname],200);
            }
        }
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = 'https://partner.ecuzen.in/new/api/account_verify';
        $sendingData = array(                                   
    		'api_key' => $api_key,
    		'ifsc' => $ifsc,
    		'lat'=>$lat,
    		'log'=>$log,
    		'account' => $account
    	);
        $response = $this->callApi($url,'POST',$sendingData, '');
        // return $response;
       /* $response = '{"txnid":"BANKVER8381488921","status":"SUCCESS","account":"677105601578","ifsc":"ICIC0006771","bname":"ECUZEN SOFTWARE PRIV","rrn":"311510984527"}';*/
        $response = json_decode($response);
        if($response->status == 'SUCCESS')
        {
             $qtra = DB::table('qtra_acc')->where('account',$account)->where('mobile' , $phone)->count();
             if($qtra > 0)
             {
                 DB::table('qtra_acc')->where('account',$account)->where('mobile' , $phone)->update(['fetch_acc' => 1, 'bname' => $response->bname]);
             }else{
                 DB::table('qtra_acc')->insert(['mobile' => $phone, 'account' => $account, 'ifsc' => $ifsc, 'bname' => $response->bname, 'fetch_acc' => 1]);
             }
             $amount = DB::table('settings')->where('name','accverify')->first()->value;
             $this->debitEntry($uid,'ACC-VERIFY',$response->txnid,$amount);
             return response()->json(['status'=>'SUCCESS','message'=>'Your Account has been Verified!', 'name' => $response->bname],200);
        }else{
            return response()->JSON(['status' => 'ERROR', 'message' => $response->msg],400);
        }
    }
    
    public function testNotification()
    {
            $notificationData = array("title" => 'Balance Enquiry', "message" => 'Test notification', "user_id" => 'TEST USER',  "notify_type" => "transaction_success","transactionID"=>'TEST123456789');
            $this->pushAndroid('dB7fvaXRTw-ulYL6RbJYgM:APA91bFDuC19qBeN7IJdu92wfFPyyoTiS2fK5NCofBLSMlPHdlKTc4eWI199hkcv8iJfB9SCsMMALvFISP-0wf5trH0IEyyf20RBHfi_BJvahW6TgzRALsaQmP9wVk8DU1349sDXVIr0', $notificationData);
    }
}
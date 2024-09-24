<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\AadharKycDetail as AadharKyc;
use Illuminate\Support\Str;

class MainController extends Controller
{
    protected function compareWallet($from,$to)
    {
        $total = DB::table('wallet')->whereBetween('date', [$from, $to])->where(['txntype'=>'CREDIT','uid'=>session('uid'),'type'=>['MSI-COMMISSION','CW-COMMISSION','RECHARGE-COMMISSION']])->sum('amount');
        return $total;
    }
    
    public function kycPending()
    {
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $supportEmail = DB::table('settings')->where('name','cemail')->first()->value;
        $supportContact = DB::table('settings')->where('name','cnumber')->first()->value;  
        return view('kyc.pending',['logo'=>$logo,'title'=>$title,'supportEmail'=>$supportEmail,'supportContact'=>$supportContact]);
    }
    
    protected function previousTransactions()
    {
        $yesterDayStart = date('Y-m-d H:i:s', strtotime(Carbon::now()->startOfDay()->subDay()));
        $yesterDayend = date('Y-m-d H:i:s', strtotime(Carbon::now()->endOfDay()->subDay()));
        $todayStart = date('Y-m-d H:i:s', strtotime(Carbon::now()->startOfDay()));
        $todayCurrent = date('Y-m-d H:i:s', strtotime(Carbon::now()));
        #AEPS
        $yseterDayAeps = DB::table('iaepstxn')->whereBetween('date', [$yesterDayStart, $yesterDayend])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('txnamount');
        $todayDayAeps = DB::table('iaepstxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('txnamount');
        #DMT
        $yseterDayDmt = DB::table('dmrtxn')->whereBetween('date', [$yesterDayStart, $yesterDayend])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        $todayDayDmt = DB::table('dmrtxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        #UTI
        $yseterDayUti = DB::table('pancoupontxn')->whereBetween('date', [$yesterDayStart, $yesterDayend])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        $todayDayUti = DB::table('pancoupontxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        #quick transfer
        $yseterDayQt = DB::table('qtransfertxn')->whereBetween('date', [$yesterDayStart, $yesterDayend])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        $todayDayQt = DB::table('qtransfertxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        #payout
        $yseterDayPayout = DB::table('payouttxn')->whereBetween('date', [$yesterDayStart, $yesterDayend])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        $todayDayPayout = DB::table('payouttxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        #bbps
        $yseterDayBbps = DB::table('bbpstxn')->whereBetween('date', [$yesterDayStart, $yesterDayend])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        $todayDayBbps = DB::table('bbpstxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS','uid'=>session('uid')])->sum('amount');
        #Recharge mobile
        $yseterDayMobile = DB::table('rechargetxn')->whereBetween('date', [$yesterDayStart, $yesterDayend])->where(['status'=>'SUCCESS','uid'=>session('uid')])->where('operator','<=',5)->sum('amount');
        $todayDayMobile = DB::table('rechargetxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS','uid'=>session('uid')])->where('operator','<=',5)->sum('amount');
        #Recharge Dth
        $yseterDayDth = DB::table('rechargetxn')->whereBetween('date', [$yesterDayStart, $yesterDayend])->where(['status'=>'SUCCESS','uid'=>session('uid')])->where('operator','>',5)->sum('amount');
        $todayDayDth = DB::table('rechargetxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS','uid'=>session('uid')])->where('operator','>',5)->sum('amount');
        
        $data = array(
            'yesterDayAeps' => $yseterDayAeps,
            'todayDayAeps' => $todayDayAeps,
            'aepsProfit' => $yseterDayAeps*($todayDayAeps/100),
            'yesterDayDmt' => $yseterDayDmt,
            'todayDayDmt' => $todayDayDmt,
            'dmtProfit' => $yseterDayDmt*($todayDayDmt/100),
            'yesterDayQt' => $yseterDayQt,
            'todayDayQt' => $todayDayQt,
            'qtProfit' => $yseterDayQt*($todayDayQt/100),
            'yesterDayPayout' => $yseterDayPayout,
            'todayDayPayout' => $todayDayPayout,
            'payoutProfit' => $yseterDayPayout*($todayDayPayout/100),
            'yesterDayBbps' => $yseterDayBbps,
            'todayDayBbps' => $todayDayBbps,
            'bbpsProfit' => $yseterDayBbps*($todayDayBbps/100),
            'yesterDayMobile' => $yseterDayMobile,
            'todayDayMobile' => $todayDayMobile,
            'mobileProfit' => $yseterDayMobile*($todayDayMobile/100),
            'yesterDayDth' => $yseterDayDth,
            'todayDayDth' => $todayDayDth,
            'dthProfit' => $yseterDayDth*($todayDayDth/100),
            'yesterDayUti' => $yseterDayUti,
            'todayDayUti' => $todayDayUti,
            'utiProfit' => $yseterDayUti*($todayDayUti/100),
            );
        return $data;
    }
    
    public function index()
    {
        
          if(null !==(session()->get('uid')))
          {
              #check for kyc
              $checkKyc = DB::table('kyc')->where('uid',session('uid'))->count();
              if($checkKyc != 1)
              return $this->kyc_form();
              #check for pending or approved
              $checkStatus = DB::table('kyc')->where('uid',session('uid'))->first();
              $title = DB::table('settings')->where('name', 'title')->first()->value;
              $logo = DB::table('settings')->where('name', 'logo')->first()->value;
              $fav = DB::table('settings')->where('name', 'favicon')->first()->value;
              $supportEmail = DB::table('settings')->where('name','cemail')->first()->value;
              $supportContact = DB::table('settings')->where('name','cnumber')->first()->value;
              if($checkStatus->active != 1)
              return view('kyc.pending',['logo'=>$logo,'title'=>$title,'supportEmail'=>$supportEmail,'supportContact'=>$supportContact]);
              $user = DB::table('users')->where('id',session('uid'))->first();
              #check previous day credit txn
              $news = DB::table('settings')->where('name','news')->first()->value;
              $previousDaytotal = $this->compareWallet(date('Y-m-d H:i:s', strtotime(Carbon::now()->startOfDay()->subDay())),date('Y-m-d H:i:s', strtotime(Carbon::now()->endOfDay()->subDay())));
              $currentDaytotal = $this->compareWallet(date('Y-m-d H:i:s', strtotime( Carbon::now()->startOfDay())),date('Y-m-d H:i:s', strtotime( Carbon::now())));
              $data = ['title' => $title,'logo' => $logo,'fav' => $fav, 'name'=>$user->name,'wallet'=>$user->wallet,'income'=>$currentDaytotal,'userRole'=>$user->role,'walletBalance'=>$user->wallet,'customerName'=>$user->name,
                'customerEmail'=>$user->email,
                'customerRole'=>DB::table('role')->where('id',$user->role)->first()->name,'news'=>$news,'profileImage'=>$user->profile];
              view()->share($this->previousTransactions());
              return view("dashboard",$data);
          }
          else
          {
              return redirect()->route('user.login');
          }
    }
    public function userLogin()
    {
        if(session()->has('uid'))
        return redirect()->route('home');
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value; 
        $fav = DB::table('settings')->where('name', 'favicon')->first()->value;
        $data = ['title' => $title,'logo' => $logo,'fav' => $fav];
        return view("login1",$data);
    }
    public function login(Request $res)
    {
       
        $username = $res->username;
        $password = $res->password;
        if($username == "" || $password == "")
        {
            echo "Please Send Proper Username And Password";
        }
        else
        {
            $uc = DB::table('users')->where('username', $username)->count();
            if($uc == 1)
            {
                $user = DB::table('users')->where('username', $username)->first();
                if($user->password == $password)
                {
                    if($user->active != 1)
                    {
                        echo "User Inactive Please Contact To Administrator";
                        exit();
                    }
                    session()->put('tid', $user->id);
                    return view("loginpin");
                }
                else
                {
                    echo "Wrong Password";
                }
            }
            else
            {
                echo "Invalid Username";
            }
        }
    }
    
    public function loginverify(Request $res)
    {
        $pin = $res->pin;
        if($pin == "")
        {
            echo "Please Send Proper Data";
            exit();
        }
        
        $tid = session()->get('tid');
        $user = DB::table('users')->where('id', $tid)->first();
        
        if($pin == $user->pin)
        {
            #insert login logs
            $insertData = array(
                'username' => $user->username,
                'ip' => $res->ip(),
                'log' => $res->log,
                'lat' => $res->lat,
                'type' => 'USER',
            );
            DB::table('loginlog')->insert($insertData);
            session()->put('uid', $user->id);
            $data = ['customerName'=>$user->name,
            'customerEmail'=>$user->email,'customerRole'=>DB::table('role')->where('id',$user->role)->first()->name];
            session()->put($data);
            echo 1;
        }
        else
        {
            echo "Invalid Pin";
        }
        
    }
    
    public function logout()
    {
        session()->forget('uid');
        return redirect()->route('user.login');
    }
    
    
    public function kyc()
    {
        if(null !==(session()->get('uid')))
        {
            $uid = session()->get('uid');
            $count = DB::table('kyc')->where('uid', $uid)->count();
            if($count == "1")
            {
                $kyc = DB::table('kyc')->where('uid', $uid)->first();
                if($kyc->active == "1")
                {
                    return redirect()->route('user.login');
                }
                else
                {
                    $title = DB::table('settings')->where('name', 'title')->first()->value;
                    $logo = DB::table('settings')->where('name', 'logo')->first()->value; 
                    $data = ['title' => $title,'logo' => $logo];
                    return view("pendingkyc",$data);
                }
            }
            else
            {
                $title = DB::table('settings')->where('name', 'title')->first()->value;
                $logo = DB::table('settings')->where('name', 'logo')->first()->value; 
                $data = ['title' => $title,'logo' => $logo];
                return view("kyc",$data);
            }
        }
        else
        {
            return redirect()->route('user.login');
        }
    }
    
    
    public function profile()
    {
        try 
        {
            if(null !==(session()->get('uid')))
            {
                
                $title = DB::table('settings')->where('name', 'title')->first()->value;
                $logo = DB::table('settings')->where('name', 'logo')->first()->value; 
                $data = ['title' => $title,'logo' => $logo];
                return view("profile",$data);
            }
            else
            {
                return redirect()->route('user.login');
            }
        } 
        catch (\PDOException $e)
        {
            return back()->with('error',$e->getMessage());;
        }
        catch (Exception $e)
        {
            return back()->with('error',$e->getMessage());;
        }
        
    }
    
    function forgotpass($type)
    {
        $view = view('forgotpass',['type'=>$type]);
        $html = $view->render();
        $data = array(
            'status' =>'SUCCESS',
            'view' => $html,
            'res_code' =>200
            );
        return json_encode($data);
    }
   public function forgotsend(Request $res)
    {
		$validator = Validator::make($res->all(), [
			'email' => 'required|email|unique:users,email',
		]);
		$type = $res->type;
		$blockedUsers = ['password', 'pin'];
		if (!in_array($type, $blockedUsers)) {
			return abort(404);
		}
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
			return response()->json(['status' => 'SUCCESS', 'message' => ucfirst($type) . ' reset link send to your registered email address', 'error' => false]);
		} else {
			return response()->json(['status' => 'ERROR', 'message' => 'This email id is not associated with any user!', 'error' => false],400);
		}
	}
   
   function addmember()
   {
       if(!null ==session('uid'))
       {
            $title = DB::table('settings')->where('name', 'title')->first()->value;
            $logo = DB::table('settings')->where('name', 'logo')->first()->value;
            $user = DB::table('users')->where('id',session('uid'))->first();
            $role = DB::table('role')->where('id','>',$user->role)->get();
            $reqmembers = DB::table('register as r')->join('role','role.id','=','r.role')->select('r.*','role.name as role_name')->where('ref_by',session('uid'))->get();
            $members = DB::table('users as u')->join('role','role.id','=','u.role')->select('u.*','role.name as role_name')->where('owner',session('uid'))->get();
            $list = array_merge(json_decode(json_encode($reqmembers)) , json_decode(json_encode($members)));
            // return $list;
            $data = ['title' => $title,'logo' => $logo,'role'=>$role,'list'=>$list,'allData'=>$list];
            return view("users/addmember",$data);
       }
       else
       {
           return redirect()->route('user.login');
       }
   }
   
   function memberreg(Request $res)
   {
       if(!null == session('uid'))
       {
           $phone = $res->phone;
           $email = $res->email;
           #check for existance 
           $check = DB::table('users')->where('phone',$phone)->orWhere('email',$email)->count();
           if($check > 0)
           return response()->json(['status'=>'ERROR','msg'=>'An account already associated with same details !!','res_code'=>422]);
           $check = DB::table('register')->where('mobile',$phone)->orWhere('email',$email)->count();
           if($check > 0)
           return response()->json(['status'=>'ERROR','msg'=>'User already registered with same details !!','res_code'=>422]);
           $name = explode(" ", $res->name);
           $first = $name[0];
           $last = str_replace($first,"",$res->name);
           $data = array(
               'first' => $first,
               'last' => $last,
               'mobile' => $phone,
               'email' => $email,
               'role' => $res->role,
               'ip' => $res->ip(),
               'ref_by' => session('uid')
               );
            $res = DB::table('register')->insert($data);
            if($res)
            return response()->json(['status'=>'SUCCESS','msg'=>'Member added successfully','res_code'=>200]);
            else
            return response()->json(['status'=>'ERROR','msg'=>'Database Error occured!!','res_code'=>500]);
       }
       else
       {
          return redirect()->route('user.login');
       }
   }
   function addfund()
   {
        if(session('uid') == null)
        return redirect()->to('/');
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $bank = DB::table('bank')->get();
        $data = ['title' => $title,'logo' => $logo,'bank'=>$bank,'date'=>Carbon::now()->format('Y-m-d')];
        return view("users/addfund",$data);
   }
   function addfundsubmit(Request $res){
       if(session('uid') != null)
       {
            $validator = Validator::make($res->all(), [
                'amount' => 'required|numeric|gt:0',
                'utr' => 'required|numeric',
                'date' => 'required|date',
                'proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','data' => $errors], 200);
            }
            #check duplicate utr
            $check = DB::table('topup')->where('rrn',$res->utr)->count();
            if($check > 0)
            return response()->json(['status'=>'ERROR','data'=>array('msg'=>array('Duplicate UTR'))]);
            if ($res->hasFile('proof')) {
                $image = $res->file('proof');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('images', $imageName, 'public');
                $imagePath = Storage::disk('public')->path('images/' . $imageName);
                $imagePath = str_replace('/www/wwwroot/','https://',$imagePath);
            }
            $txnid = "ECZ".rand(1000000000,9999999999).'T';
            $amount = $res->amount;
            $utr = $res->utr;
            $data = array(
                'amount'  =>$amount,
                'txnid' => $txnid,
                'proof' => $imagePath,
                'status' => 'PENDING',
                'bank' => $res->bid,
                'uid' => session('uid'),
                'rrn' => $utr,
                'transaction_date' => $res->date,
                );
            $res = DB::table('topup')->insert($data);
            if(! $res)
            {
                $data = array(
                    'msg' => 'Database error'
                    );
                return response()->json(['status'=>'ERROR','data'=>$data],500);
            }
            $data = [
                'txnid' => $txnid,
                'amount' => $amount,
                'username' => DB::table('users')->where('id',session('uid'))->get()->pluck('username'),
                'rrn' => $utr,
                'view' => 'mail.admin.addfund',
                'subject' => 'Wallet fund request'
            ];
            $adminmail = DB::table('admin')->where('id',1)->get()->pluck('email');
            Mail::to($adminmail)->send(new SendEmail($data));
            return response()->json(['status'=>'SUCCESS','msg' => 'Request submitted successfully Wait for admin response and Please keep your txnid for further use '.$txnid],200);
       }
       else
       {
           return redirect()->route('user.login');
       }
   }
   
   function kyc_form()
   {
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        
        $user = DB::table('users')->where('id',session('uid'))->get()->first();
        $string = $user->name;
        $length = strlen($string);
        $splitPosition = floor($length / 2) + 1;
        $firstname = substr($string, 0, $splitPosition);
        $lastname = substr($string, $splitPosition);
        $states = DB::table('icicstate')->get();
        $data = ['title' => $title,'logo' => $logo,'firstname'=>$firstname,'lastname'=>$lastname,'phone'=>$user->phone,'email'=>$user->email,'states'=>$states];
        
        $findKycType = DB::table('settings')->where('name', 'kyc_type')->first()->value;
        // if($findKycType == 'aadhar')
        // return view('kyc.aadhar',$data);
        return view('kyc2',$data);
   }
    
    public function submitkyc(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'emailaddress' => 'required|email',
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
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','message' => 'Some fields are missing'.$errors], 200);
            }
        #check for already existance
        $aadhar = $res->aadharNumber;
        $check = DB::table('kyc')->where('adhaar',$aadhar)->where('uid',session('uid'))->count();
        if($check == 0)
        {
            $uid = session('uid');
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
                'address' =>$address,
                'pincode' => $pincode,
                'pan' => strtoupper($pan),
                'adhaar' => $aadhar,
                'adhaarimg' => $adhaarimg,
                'panimg' => $panimg,
                'shopname' => $shopname,
                'shopaddress' =>$shopaddress,
                'active' => 0,
                'state'=>$state,
                'district'=> $district,
                'adhaarback' => $adhaarback,
                'fname' => $fname,
                'dob' => $dob
                );
            $res = $this->insertdata('kyc',$sendingData);
            if($res)
            return response()->json(['status'=>'SUCCESS','message'=>'You KYC has been completed please wait for admin to approve your KYC request!!']);
            return response()->json(['status'>'ERROR','message'=>'Database errors']);
            
        }
        else
        {
            return response()->json(['status'=>'INFO','message'=>'You already fill your KYC please wait untill admin approve your kyc!']);
        }
    }
    
    public function sendEmail()
    {
        $data = [
            'username' => 'John Doe',
            'password' => '1234567',
            'pin' =>1234,
            'view' => 'mail.credential',
            'subject' => 'User credentials'
        ];
        Mail::to('vinay@ecuzen.com')->send(new SendEmail($data));
    
        return "Email sent successfully!";
    }
   
    public function commCharge()
    {
        try
        {
            $user = DB::table('users')->where('id',session('uid'))->first();
        
            $title = DB::table('settings')->where('name', 'title')->first()->value;
            $logo = DB::table('settings')->where('name', 'logo')->first()->value;
            
            $data = ['title' => $title,'logo' => $logo];
            $data['labeles'] = DB::table('commtables')->select('id','label')->get();
            $data['packages'] = DB::table('package')->select('name','id')->where('id',$user->package)->get();
            return view("users.commCharge",$data);    
        }
        catch (\PDOException $e)
        {
            return redirect()->route('home')->with('error',$e->getMessage());
        }
        catch (Exception $e)
        {
            return redirect()->route('home')->with('error',$e->getMessage());
        }
        
    }
    
    public function fetchCommCharge($item,$package)
    {
         #check item 
        $checkItem = DB::table('commtables')->where('id',$item)->count();
        if($checkItem != 1)
        return response()->json(['status'=>'ERROR','message'=>'Invalid type!']);
        #check package 
        $checkPackage = DB::table('package')->where('id',$package)->count();
        if($checkPackage != 1)
        return response()->json(['status'=>'ERROR','message'=>'Invalid Package!']);
        #get table name 
        $tableData = DB::table('commtables')->where('id',$item)->select('table_name','bracket','label')->get()[0];
        $getDetails = DB::table($tableData->table_name)->where('package',$package)->get();
        if($item == 10)
        $getDetails = DB::table('comissionv2')->join('rechargeop', 'rechargeop.code', '=', 'comissionv2.operator')->where('comissionv2.package', '=', $package)->select("comissionv2.*","rechargeop.name as operator")->get();
        if($item == 13)
        $getDetails = DB::table('bbps')->join('bbpsincat', 'bbpsincat.id', '=', 'bbps.opid')->where('bbps.package', '=', $package)->select("bbps.*","bbpsincat.name as opid")->get();
        if (Schema::hasTable($tableData->table_name)) {
            $columns = Schema::getColumnListing($tableData->table_name);
        }
        $elementsToRemove = ["id", "commission", "package"];
        $newColumn = array_diff($columns,$elementsToRemove);
        $newColumn = array_values($newColumn);
        $data = array(
            'tableData' => $getDetails,
            'bracket' => $tableData->bracket,
            'label' => $tableData->label,
            'previlage' => 'user',
            'table' => $tableData->table_name,
            'package' => $package,
            'column' => $newColumn
            );
        return response()->json(['status'=>'SUCCESS','view'=>view('admin.package.fetch',$data)->render()]);
        return view('admin.package.fetch',$data);
    }
    
    public function raiseTicket()
    {
        try
        {
            $myTicket = DB::table('ticket')->where('uid',session('uid'))->orderBy('id','desc')->limit(5)->get();
            $supportType = DB::table('support')->get();
            $title = DB::table('settings')->where('name', 'title')->first()->value;
            $logo = DB::table('settings')->where('name', 'logo')->first()->value;
            $data = ['title' => $title,'logo' => $logo,'tickets' => $myTicket,'supportType'=>$supportType];
            return view("users.supportTicket",$data);    
        }
        catch (\PDOException $e)
        {
            return redirect()->route('home')->with('error',$e->getMessage());
        }
        catch (Exception $e)
        {
            return redirect()->route('home')->with('error',$e->getMessage());
        }
        
    }
    
    public function submitComplain(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'service' => 'required|string',
                'txnid' => 'required|string',
                'message' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status'=>'ERROR','message' => $validator->errors()->first()], 200);
            }
        $service = $res->service;
        $txnid = $res->txnid;
        $message = $res->message;
        $ticketId = 'TKT'.uniqid();
        $insertData = array(
            'uid' => session('uid'),
            'ticketid' => $ticketId,
            'service' => $service,
            'txnid' => $txnid,
            'message' => $message,
            'status' => 'PENDING'
            );
        $res = DB::table('ticket')->insert($insertData);
        if($res)
        return response()->json(['status'=>'SUCCESS','message'=>'Ticket raised successfully!!']);
        return response()->json(['status'=>'ERROR','message'=>'Internal server error']);
    }
    
    public function userProfileSettings()
    {
        $user = User::find(session('uid'));
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $data = ['title' => $title,'logo' => $logo,'name' => $user->name,'phone' => $user->phone,'email' => $user->email,'profile'=>$user->profile];
        return view("users.profile",$data);
    }
    
    
    public function updateProfileSettings(Request $res,$type)
    {
        $allowedType = ['profile','details','pin','password'];
        if(!in_array($type,$allowedType))
        return response()->json(['status'=>'ERROR','message'=>'Invalid request!!']);
        $uid = session('uid');
        switch ($type) {
            case 'profile':
                    $validator = Validator::make($res->all(), [
                        'profile' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['status'=>'ERROR','message' => $validator->errors()->first()], 200);
                    }
                    User::where('id',$uid)->update(['profile' => $this->upliadFile($res->file('profile'))]);
                    return response()->json(['status'=>'SUCCESS','message'=>'Profile Picture updated successfully']);
                break;
            case 'details':
                    $validator = Validator::make($res->all(), [
                        'name' => 'required|string',
                        'phone' => 'required|numeric|digits:10',
                        'email' => 'required|email',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['status'=>'ERROR','message' => $validator->errors()->first()], 200);
                    }
                    User::where('id',$uid)->update(['name'=>$res->name,'phone'=>$res->phone,'email'=>$res->email]);
                    return response()->json(['status'=>'SUCCESS','message'=>'Profile details updated successfully']);
                break;
            case 'pin':
            case 'password':
                    $validator = Validator::make($res->all(), [
                        'current'.$type => 'required|string',
                        'new'.$type => 'required|string',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['status'=>'ERROR','message' => $validator->errors()->first()], 200);
                    }
                    $user = User::find($uid);
                    $getnew = "new".$type;
                    $getcurrent = "current".$type;
                    $currentValue = $res->$getcurrent;
                    $newValue = $res->$getnew;
                    if($user->$type != $currentValue)
                    return response()->json(['status'=>'ERROR','message'=>'Invalid current '.$type]);
                    User::where('id',$uid)->update([$type => $newValue]);
                    return response()->json(['status'=>'SUCCESS','message'=>ucfirst($type).' updated successfully']);
                break;
            default:
                return view('errors.404');
                break;
        }
    }
    #wallet to wallet
    public function walletTowallet()
    {
        try
        {
            $title = DB::table('settings')->where('name', 'title')->first()->value;
            $logo = DB::table('settings')->where('name', 'logo')->first()->value;
            $latestTxns = DB::table('wallet_to_wallet')->where('sender',session('uid'))->orWhere('receiver',session('uid'))->orderBy('id', 'desc')->limit(5)->get();
            $txns = array();
            foreach ($latestTxns as $txn)
            {
                if($txn->receiver == session('uid'))
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
                    'txnid' => $txn->txnid,
                    'amount' => $txn->amount,
                    'date' => $txn->date,
                    );
                $txns[] = $txnData;
            }
            $data = ['title' => $title,'logo' => $logo,'latestTxns'=>$txns];
            return view("users.wallettowallet",$data);
        }
        catch (\PDOException $e)
        {
            return redirect()->route('home')->with('error',$e->getMessage());
        }
        catch (Exception $e)
        {
            return redirect()->route('home')->with('error',$e->getMessage());
        }
    }
    
    public function fetchUser($mobile)
    {
        $self = User::find(session('uid'));
        if($self->phone == $mobile)
        return response()->json(['status'=>'ERROR','message'=>'Cant send money to your self!!']);
        $checkUser = User::where('phone',$mobile)->first();
        if($checkUser == "" || $checkUser == null)
        return response()->json(['status'=>'ERROR','message'=>'User not associated with this number']);
        $returnData = array(
            'username' => $checkUser->username,
            'name' => $checkUser->name,
            );
        return response()->json(['status'=>'SUCCESS','message'=>'User found successfully!!','data'=>$returnData]);
    }
    
    public function walletToWalletCredit(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'mobile' => 'required|numeric|digits:10',
            'amount' => 'required|numeric|gt:0',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERROR','message' => $validator->errors()->first()], 200);
        }
        $mobile = $res->mobile;
        $amount = $res->amount;
        $uid = session('uid');
        $user = User::find($uid);
        /*$check = $this->checkservice('walletTowallet',$uid);
        if($check->status == 'ERROR')
        return $check;
        $charge = $this->checkcharge($amount,'wallet_to_wallet_charges',$user->package);
        if($charge != true)
        return response()->json(['status'=>'INFO','message'=>'Charges are not set for you please contact to admin!']);
        if($user->wallet < $charge+$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet. You need '.$charge+$amount.' to do this transaction']);*/
        if($amount > $user->wallet)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet']);
        if($mobile == $user->phone)
        return response()->json(['status'=>'ERROR','message'=>'Cant send money to your self!!']);
        $receiverUser = User::where('phone',$mobile)->first();
        if($receiverUser == "" || $receiverUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Receiver user not found']);
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
        return response()->json(['status'=>'SUCCESS','view'=>view('receipt.wallettowallet',$data)->render()],200);
    }
    public function accountVerify(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'account' => 'required|numeric',
            'ifsc' => 'required|string',
            'phone' => 'required|numeric|digits:10'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERROR','message' => $validator->errors()->first()], 200);
        }
        $uid = session('uid');
        $user = User::find($uid);
        $username = $user->username;
        $loginlog = DB::table('loginlog')->where('username',$username)->first();
        $wallet = $this->checkwallet($uid);
        $charge = DB::table('accountVerify')->where('package',$user->package)->first()->amount;
        if($wallet < $charge)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet!!'],200);
        $account = $res->account;
        $ifsc = $res->ifsc;
        $phone = $res->phone;
        
        $checkacc = DB::table('qtra_acc')->where('account',$account);
        if($checkacc->count() > 0){
             if($checkacc->first()->fetch_acc == 1)
            {
                return response()->json(['status'=>'SUCCESS','message'=>'Your Account Allready Featched!!', 'name' => $checkacc->first()->bname],200);
            }
        }
        $api_key = DB::table('settings')->where('name','api_key')->first()->value;
        $url = 'https://partner.ecuzen.in/new/api/account_verify';
        $sendingData = array(                                   
    		'api_key' => $api_key,
    		'ifsc' => $ifsc,
    		'lat'=>$loginlog->lat??'26.8587263',
    		'log'=>$loginlog->log??'77.2145152',
    		'account' => $account
    	);
        $response = $this->callApi($url,'POST',$sendingData, '');
        $response = json_decode($response);
        
        if($response->status == 'SUCCESS')
        {
             $qtra = DB::table('qtra_acc')->where('account',$account)->count();
             if($qtra > 0)
             {
                 DB::table('qtra_acc')->where('account',$account)->update(['fetch_acc' => 1, 'bname' => $response->bname]);
             }else{
                 DB::table('qtra_acc')->insert(['mobile' => $phone, 'account' => $account, 'ifsc' => $ifsc, 'bname' => $response->bname, 'fetch_acc' => 1]);
             }
             $this->debitEntry($uid,'ACC-VERIFY',$response->txnid,$charge);
             return response()->json(['status'=>'SUCCESS','message'=>'Name Fetch successfully!!', 'name' => $response->bname],200);
        }else{
            return response()->JSON(['status' => 'ERROR', 'message' => $response->msg]);
        }
    }
    public function signup(){
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value; 
        $role = DB::table('role')->get();
        $data = ['title' => $title,'logo' => $logo, 'role'=>$role];
        return view("users.register",$data); 
    }
    public function registerUser(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'firstName' => 'required|string',
                'lastName' => 'required|string',
                'email' => 'required|email|unique:users,email|unique:register,email',
                'phone' => 'required|numeric|unique:users,phone|unique:register,mobile|digits:10',
            ]);
            if ($validator->fails()) {
                return response()->json(['status'=>'ERROR','message' => $validator->errors()->first()], 200);
            }
            $fname = $res->firstName;
            $lname = $res->lastName;
            $email = $res->email;
            $mobile = $res->phone;
            $insertData = array(
                'first' => $fname,
                'last' => $lname,
                'mobile' => $mobile,
                'email' => $email,
                'role' => $res->role,
                'ip' => $res->ip(),
                'ref_by' => 0,
                );
        try{
            $res = DB::table('register')->insert($insertData);
            if($res)
            return response()->json(['status'=>'SUCCESS','message'=>'Registered successfully, Please wait for admin to approve your request..']);
        }
        catch(\Exception $e)
        {
            return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
        }
    }
    public function otherServices()
    {
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $news = DB::table('settings')->where('name', 'news')->first()->value;
        $otherService = DB::table('otherservices')->get();
        $data = ['title' => $title,'logo' => $logo,'news' => $news,'otherservices'=>$otherService];
        return view('users.otherServices',$data);
    }
    public function aadhar_send_otp(Request $req)
    {
        try
        {
            $validator = Validator::make($req->all(), [
                    'aadhar' => 'required|numeric|digits:12',
                ]);
            if ($validator->fails()) {
                return response()->json(['status'=>'ERROR','message' => $validator->errors()->first(),'info' => $validator->errors()]);
            }
            $url = 'https://indicpay.in/api/aadhaar_verification/send_otp';
            $apikey = DB::table('settings')->where('name','indicpay_token')->first()->value;
            $data = ["aadhaar_no" => $req->aadhar];
            $headers = ["token: $apikey"];
            $response = $this->makeCurlRequest($url, 'POST', $data, $headers);
            // $response = '{"status":"SUCCESS","msg":"OTP Send Successfully","txnid":"AV65968AE5B2256"}';
            $response = json_decode($response);
            if($response->status == 'SUCCESS')
            {
                return response()->json(['status' => 'SUCCESS' ,'message' => 'OTP send to your registered mobile number','reference' => $response->txnid]);
            }
            return response()->json(['status' => 'ERROR' ,'message' => $response->msg??'Unknown error']);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => 'ERROR' , 'message' => $e->getMessage()]);
        }
    }
    private function generatePng($string)
    {
        $imageData = base64_decode($string);
        $imageName = uniqid('image_') . '.png';
        Storage::disk('public')->put('images/' . $imageName, $imageData);
        $imageUrl = url('app/public/images/' . $imageName);
        return $imageUrl;
    }
    public function aadhar_validate_otp(Request $req)
    {
        try
        {
            $validator = Validator::make($req->all(), [
                    'otp' => 'required|numeric',
                    'txnid' => 'required',
                ]);
            if ($validator->fails()) {
                return response()->json(['status'=>'ERROR','message' => $validator->errors()->first(),'info' => $validator->errors()]);
            }
            $uid = session('uid');
            #check existance 
            $check = AadharKyc::where('uid',$uid)->count();
            if($check > 0)
            return response()->json(['status' => 'ERROR' ,'message' => 'You already applied for kyc']);
            $url = 'https://indicpay.in/api/aadhaar_verification/fetch_details';
            $apikey = DB::table('settings')->where('name','indicpay_token')->first()->value;
            $data = ["txnid" => $req->txnid,'otp' => $req->otp];
            $headers = ["token: $apikey"];
            $response = $this->makeCurlRequest($url, 'POST', $data, $headers);
            $response = json_decode($response);
            if($response->status == 'SUCCESS')
            {
                $aadharData = $response->data;
                AadharKyc::create([
                    "uid" => $uid,	
                    "aadhar" => $aadharData->uid,	
                    "name" => $aadharData->name,	
                    "mobile" => $aadharData->mobile,	
                    "email" => $aadharData->email,	
                    "dob" => $aadharData->dob,	
                    "gender" => $aadharData->gender,	
                    "profile" => $this->generatePng($aadharData->residentPhoto),	
                    "father_name" => $aadharData->careof,	
                    "address" => $aadharData->street,	
                    "post" => $aadharData->poName,	
                    "district" => $aadharData->districtName,	
                    "state" => $aadharData->stateName,	
                    "pincode" => $aadharData->pincode,	
                    "response" => json_encode($response),
                    ]);
                return response()->json(['status' => 'SUCCESS','message' => 'Basic KYC details fetched successfully']);
            }
            return response()->json(['status' => 'ERROR' ,'message' => $response->msg??'Unknown error']);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => 'ERROR' , 'message' => $e->getMessage()]);
        }
    }
    public function pan_verification_details(Request $req)
    {
        try{
            $validator = Validator::make($req->all(), [
                'pan' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
                'lat' => 'required',
                'log' => 'required',
            ], [
                'pan.regex' => 'The PAN number is not in the correct format.',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','message' => $errors->first(),'info' => $errors], $req->req == 'api' ? 400 : 200);
            }
            $user = $req->user();
            $chargeRow = DB::table('pan_verification_charges')->where('package',$user->package)->first();
            if($chargeRow == "" || $chargeRow == null)
            return response()->json(['status'=>'ERROR','message'=>'Commission is not set for you please contact to admin!'],$req->req == 'api' ? 422 : 200);
            if($chargeRow->percent == 1)
            $charge = $chargeRow->amount/100;
            else
            $charge = $chargeRow->amount;
            if($user->wallet < $charge)
            return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in your wallet please recharge your wallet'],$req->req == 'api' ? 402 : 200);
            
            $pan = $req->pan;
            #if already VALID
            $check = DB::table('pan_verification_txns')->where('pan_number',$pan)->where('status','SUCCESS')->first();
            if($check != null)
            {
                $data = $check->response;
                $data = json_decode($data);
                $apiData = $data->data;
                $returnData = array(
                    "name" => $apiData->name->full,
                    "pan_status" => $apiData->status,
                    "gender" => $apiData->gender??'NA',
                    "dob" => $apiData->dob??'',
                    "constitution" => $apiData->constitution
                    );
                return $req->req == 'api' ? response()->json(['status' => 'SUCCESS' ,'message' => 'Details fetched successfully!!','data' => ['pan_details' => $returnData]]) : response()->json(['status' => 'SUCCESS' ,'message' => 'PAN Details fetched successfully','view' => view('receipt.pan',$returnData)->render()]);
            }
            $url = 'https://indicpay.in/api/pan_verification';
            $apikey = DB::table('settings')->where('name','indicpay_token')->first()->value;
            $txnid = 'PAN'.strtoupper(uniqid()).'VER';
            $bodyData = ["pan_no" => $pan,"lat" => $req->lat,"long" => $req->log,"txnid" => $txnid];
            $headers = ["token: $apikey"];
            $response = $this->makeCurlRequest($url, 'POST', $bodyData, $headers);
            // $response = '{"status":"SUCCESS","msg":"Transaction Successful","data":{"name":{"first":"MANAS","middle":"KUMAR","last":"PARIDA","full":"MANAS KUMAR PARIDA"},"status":"VALID","panNumber":"XXXXXXX86M","gender":null,"aadhaarSeeding":1,"lastModified":"2021-12-02","dob":null,"constitution":"Individual"},"resc":"--1"}';
            $response = json_decode($response);
            $insertData = array(
                "uid" => $user->id,	
                "txnid" => $txnid,	
                "charge" => $charge,	
                "pan_number" => $pan,	
                "request" => json_encode(['url' => $url,'headers' => $headers,'body' => $bodyData]),	
                "response" => json_encode($response),
                'status' => $response->status,
                'platform' => $req->req,
                );
            DB::table('pan_verification_txns')->insert($insertData);
            if($response->status == 'SUCCESS')
            {
                $apiData = $response->data;
                DB::table('pan_verification_txns')->where('txnid',$txnid)->update(['name' => $apiData->name->full]);
                $this->debitEntry($user->id,'PAN-VERIFICATION',$txnid,$charge);
                $returnData = array(
                    "name" => $apiData->name->full,
                    "pan_status" => $apiData->status,
                    "gender" => $apiData->gender??'NA',
                    "dob" => $apiData->dob??'',
                    "constitution" => $apiData->constitution
                    );
                return $req->req == 'api' ? response()->json(['status' => 'SUCCESS' ,'message' => 'Details fetched successfully!!','data' => ['pan_details' => $returnData]]) : response()->json(['status' => 'SUCCESS' ,'message' => 'PAN Details fetched successfully','view' => view('receipt.pan',$returnData)->render()]);
            }
            else
            {
                return response()->json(['status' => 'ERROR','message' => $response->msg??'Unknown Error occoured'],$req->req == 'api' ? 422 : 200);
            }
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => 'ERROR','message' => $e->getMessage()],$req->req == 'api' ? 500 : 200);
        }
    }
    
    
   /* function testt()
    {
       $txnId = rand(1000000000,9999999999);
         $sending = [
           'name' => 'vinay',
           'email' => 'vinay@ecuzen.com',
           'phone' => '8824371650',
           'amount' => '2',
           'txnid' => $txnId,
           'return_url' => "https://indicpay.in/newc/redirectnew?txnid=$txnId",
           'token' => '208ac1618c84dc7df09ed6fdc156e2',
           ];
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://indicpay.in/api/btt/createorder',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($sending),
          CURLOPT_HTTPHEADER => array(
            
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        echo '<pre>';
        print_r( json_decode($response) );
        die;
        
        return $response;
    }*/
    
    /*function testt(){
        $txnid=uniqid();
        $token="snvsadvshdvsgdgvskhgdvskghdvgsdkhy";
        $account="917726828026";
        $ifsc='PYTM0123456';
        $mode="IMPS";
        $amount=3;
        $name="Mewaram kansodiya";
        $mid="ECZ197128";
        $reqData=[
            "txnid"=>$txnid,
            "token"=>$token, 
            "account"=>$account, 
            "ifsc"=>$ifsc, 
            "mode"=>$mode, 
            "amount"=>$amount, 
            "name"=>$name, 
            "mid"=>$mid
            ];
        $iv=1234567890987654; 
        $encryptedJson=$this->encrypt($token,$iv,base64_encode(json_encode($reqData)));
        $hash=hash('sha512', "$token,$mid,$txnid");
        $body=[
            'body'=>$encryptedJson,
            'hash'=>$hash
            ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://indicpay.in/api/payout/dopayout',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($body),
          CURLOPT_HTTPHEADER => array(
            'Iv: '.$iv,
            'Token: '.$token,
            'Mid: '.$mid,
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    function encrypt($token,$iv,$base64Encoded){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://indicpay.in/api/encryption',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode([
            "base64encodedata"=> $base64Encoded,
            "mid"=> $token,
            "iv"=> $iv
        ]),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    */
    
    
    
    
    /*function testt(){
        $txnid=uniqid();
        $token="snvsadvshdvsgdgvskhgdvskghdvgsdkhy";
        $amount=1;
        $name="Mewaram kansodiya";
        $mid="ECZ197128";
        $reqData=[
            "utxnid"=>$txnid,
            "token"=>$token, 
            "amount"=>$amount,
            "mid"=>$mid
            ];
        $iv=9999888888878890; 
        $encryptedJson=$this->encrypt($mid,$iv,base64_encode(json_encode($reqData)));
        $hash=hash('sha512', "$token,$mid,$txnid");
        $body=[
            'body'=>$encryptedJson,
            'hash'=>$hash
            ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://indicpay.in/api/upi/get_dynamic_qr',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($body),
          CURLOPT_HTTPHEADER => array(
            'Iv: '.$iv,
            'Token: '.$token,
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
    function encrypt($token,$iv,$base64Encoded){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://indicpay.in/api/encryption',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode([
            "base64encodedata"=> $base64Encoded,
            "mid"=> $token,
            "iv"=> $iv
        ]),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    
    
    */
    
    /*function testt()
    {
       
        $name = 'vinay';
        $txnid = 'VS'.time(). mt_rand(10000, 99999);
        $email = 'vinay@gmail.com';
        $amount = '3';
        $mobile = '8233103498';
         $return_url = "https://indicpay.in/newc/redirectnew?txnid=$txnid";
       

        $sending = [
            'name' => $name,
            'email' => $email,
            'txnid' => $txnid,
            'phone' => $mobile,
            'token' => 'a07223b941f06469c4b3ccadcb3080',
            'amount' => $amount,
            'return_url' => 'https://demo.ecuzenproducts.in/api/pg',
        ];

        $url = 'https://indicpay.in/api/btt/createorder';
       
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>json_encode($sending),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
       
        
        $response = json_decode($response);
         return $return_url;
    }*/
    
    /*function testt()
    {
       
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://indicpay.in/api/validate_vpa',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'Token: 43a4375761558d0f9d203c8b3d2e52'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }
    
    */
    
    
    
    
}

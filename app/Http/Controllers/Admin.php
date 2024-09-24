<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminModel;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use App\Models\EmployeeValidation;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Schema;


class Admin extends Controller
{
    function git(){
        return "j";
    }
    protected function common()
    {
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
         $fav = DB::table('settings')->where('name', 'favicon')->first()->value;
        $dName = $wordsArray = explode(' ', $title);
        $dName = $wordsArray[0];
        return array('logo' => $logo,'title' => $title,'dName' => $dName,'fav' => $fav);
    }
    protected function overalCommission($from,$to)
    {
        $commissionTypes = ['MSI-COMMISSION','CW-COMMISSION','AP-COMMISSION','BBPS-COMMISSION','DMT-Commission','DMT-COMMISSION','PYT-Commission','PYT-COMMISSION','QT-Commission','QT-COMMISSION','RECHARGE-COMMISSION','AP-Commission'];
        $chargesTypes = ['AP-CHARGE','DMT-CHARGE','DMT-CHARGE-R','Payout-charge','PAYOUT-CHARGE','PAYOUT-CHARGE-REFUND','QT-Charge','QT-CHARGE','QT-CHARGE-R','RECHARGE-COMMISSION-R','QT-COMM-R','PAYOUT-COMMISSION-R'];
        $totalCommissionAmount = DB::table('wallet')->whereBetween('date', [$from, $to])->where(['txntype'=>'CREDIT'])->whereIn('type',$commissionTypes)->sum('amount');
        $totalChargeAmount = DB::table('wallet')->whereBetween('date', [$from, $to])->where(['txntype'=>'DEBIT'])->whereIn('type',$chargesTypes)->sum('amount');
        return (double)$totalChargeAmount - (double)$totalCommissionAmount;
    }
     protected function requiredData()
    {
        return Cache::remember('previous_transaction', now()->addMinutes(5), function () {
        
        $todayStart = date('Y-m-d H:i:s', strtotime(Carbon::now()->startOfDay()));
        $todayCurrent = date('Y-m-d H:i:s', strtotime(Carbon::now()));
        $previousMonths = date('Y-m-d H:i:s', strtotime(Carbon::now()->startOfDay()->subMonth()));
        $todayDayBbps = DB::table('bbpstxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS'])->sum('amount');
        $todayDayDmt = DB::table('dmrtxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS'])->sum('amount');
        $todayDayUti = DB::table('pancoupontxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS'])->sum('amount');
        $todayDayAeps = DB::table('iaepstxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS'])->whereIn('type',['CW','AP'])->sum('txnamount');
        $todayDayPayout = DB::table('payouttxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS'])->sum('amount');
        $todayDayMobile = DB::table('rechargetxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS'])->where('operator','<=',5)->sum('amount');
        $todayDayDth = DB::table('rechargetxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS'])->where('operator','>',5)->sum('amount');
        $todayDayQt = DB::table('qtransfertxn')->whereBetween('date', [$todayStart, $todayCurrent])->where(['status'=>'SUCCESS'])->sum('amount');
        $todayQtCharge = DB::table('wallet')->whereBetween('date', [$todayStart, $todayCurrent])->where('type','QT-Charge')->sum('amount');
        $monthlyQtCharge = DB::table('wallet')->whereBetween('date', [$previousMonths, $todayCurrent])->where('type','QT-Charge')->sum('amount');
        $totalUsers = User::count();
        $activeUsers = User::where('active',1)->count();
        $totalAepsDone = DB::table('aepskyc')->count();
        $data = array(
            'todayBbps' => $todayDayBbps,
            'todayMobile' => $todayDayMobile,
            'todayDth' => $todayDayDth,
            'todayPayout' => $todayDayPayout,
            'todayQt' => $todayDayQt,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'deactiveUsers' => $totalUsers- $activeUsers,
            'totalUserWallet' => round(User::sum('wallet'),2),
            'totalUserAmount' => round(User::sum('wallet'),2),
            'todayQtCharge' => $todayQtCharge,
            'monthlyQtCharge' => $monthlyQtCharge,
            'todayAeps' => $todayDayAeps,
            'todayDmt' => $todayDayDmt,
            'todayUti' => $todayDayUti,
            'aepsDone' => $totalAepsDone,
            'aepsNotDone' => $totalUsers - $totalAepsDone,
            'todayCommission' => $this->overalCommission($todayStart,$todayCurrent),
            'monthlyCommission' => $this->overalCommission($previousMonths,$todayCurrent),
            );
        return $data;
        });
    }
    public function index()
    {
        $data = $this->common();
        $data['tname'] = 'Dashboard';
        if(session('aid') != null)
        {
            $data['adminName'] = AdminModel::find(session('aid'))->name;
            view()->share($this->requiredData());
            return view('admin.dashboard',$data);
        }
        else
        {
            return view('admin.login',$data);
        }
    }
    public function login(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','message' => $errors], 200);
        }
        $username = $res->username;
        $password = $res->password;
        #check username
        $checkUsername = AdminModel::where('username', $username)->count();
        if($checkUsername != 1)
        return response()->json(['status'=>'ERROR','message'=>'Invalid Username!!']);
        #check password
        $adminRow = AdminModel::where('username', $username)->first();
        $adminPassword = $adminRow->password;
        if($adminPassword != $password)
        return response()->json(['status'=>'ERROR','message'=>'Invalid Password!!']);
        $adminId = session()->put('taid',$adminRow->id);
        $otp = '313110';
        session()->put('otp',$otp);
        #doing otp section
        $sms_api = DB::table('settings')->where('name','sms_api')->first()->value;
        $senderid = "ECZSPL";
        $message = "Welcome To B2B Software Dear " . $adminRow->name . " Your One Time Password Is " . $otp . " ";
        // $link = "https://india1sms.com/api/smsg/sendsms?APIKey=".urlencode($sms_api)."&senderid=".urlencode($senderid)."&number=".urlencode($adminRow->phone)."&message=".urlencode($message)."";
        // $res = $this->callApi($link,'POST','','');
        return response()->json(['status'=>'SUCCESS','view'=>view('admin.login-otp')->render()]);
    }
    
    public function loginVverify(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'otp' => 'required|numeric',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','message' => $errors], 200);
        }
        if(session('taid') == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid login!!']);
        $otp = $res->otp;
        if(session('otp') != $otp)
        return response()->json(['status'=>'ERROR','message'=>'Invalid OTP']);
        #insert login logs
        $adminRow = AdminModel::where('id', session('taid'))->first();
        $insertData = array(
            'username' => $adminRow->username,
            'ip' => $res->ip(),
            'log' => $res->log,
            'lat' => $res->lat,
            'type' => 'ADMIN',
        );
        DB::table('loginlog')->insert($insertData);
        session()->put('aid',$adminRow->id);
        return response()->json(['status'=>'SUCCESS','message'=>'Login successfully']);
    }
    
    public function listMember($type)
    {
        $blockedUsers = ['1', '2', '3', '4', '0'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $listUser = [];
        if($type == 0)
        $listUser = User::join('role', 'users.role', '=', 'role.id')->get(['users.id as id','users.name','users.username','users.phone','users.email','users.wallet', 'role.name as roleName','users.active']);
        else
        $listUser = User::where('role', $type)->join('role', 'users.role', '=', 'role.id')->get(['users.*', 'role.name as roleName']);
        $data = $this->common();
        $data['listMember'] = $listUser;
        $data['tname'] = 'Member List';
        return view('admin.listMember',$data);
    }
    public function userLogin($memberId)
    {
        #check memberId is valid or invalid
        $checkUser = User::find($memberId);
        if($checkUser == "" || $checkUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid User!!']);
        
        $checkkyc = DB::table('kyc')->where(['uid' => $memberId, 'active' => 0])->count();
        if($checkkyc > 0){
            return response()->json(['status'=>'ERROR','message'=>'Please Approve User Kyc!!!']);
        }
        
        
        session()->put('uid',$checkUser->id);
        return response()->json(['status'=>'SUCCESS','message'=>'Login to '.$checkUser->username]);
    }
    public function userView($memberId)
    {
        #check memberId is valid or invalid
        $checkUser = User::find($memberId);
        if($checkUser == "" || $checkUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid User!!']);
        #check profile kyc
        $checkKyc = DB::table('kyc')->where('uid',$memberId)->first();
        if($checkKyc == "" || $checkKyc == null)
        $kycStatus = 'KYC Not done';
        else
        {
            if($checkKyc->active != 1)
            $kycStatus = 'KYC is in pending';
            else
            $kycStatus = 'KYC done';
        }
        #aeps kyc
        $checkKycaeps = DB::table('aepskyc')->where('uid',$memberId)->first();
        if($checkKycaeps == "" || $checkKycaeps == null)
        $kycStatusaeps = 'AEPS KYC Not done';
        else
        {
            if($checkKycaeps->status == 1)
            $kycStatusaeps = 'Waiting for OTP verification';
            elseif($checkKycaeps->status == 2)
            $kycStatusaeps = 'Waiting for Biomatric verification';
            else
            $kycStatusaeps = 'AEPS KYC done';
        }
        $checkUser = $checkUser->toArray();
        $checkUser['profileKyc'] = $kycStatus;
        $checkUser['aepsKyc'] = $kycStatusaeps;
        return response()->json(['status'=>'SUCCESS','view'=>view('admin.user.viewUser',$checkUser)->render()]);
    }
    public function userService($memberId)
    {
        #check memberId is valid or invalid
        $checkUser = User::find($memberId);
        if($checkUser == "" || $checkUser == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid User!!']);
        return response()->json(['status'=>'SUCCESS','view'=>view('admin.user.service',$checkUser)->render()]);
    }
    public function adminLogout()
    {
        session()->forget('aid');
        return redirect()->route('admin');
    }
    public function adminAdduser()
    {
        $data = $this->common();
        $data['role'] = DB::table('role')->select('name','id')->get();
        $data['package'] = DB::table('package')->select('name','id','role')->get();
        $data['tname'] = 'Add User';
        return view('admin.user.addUser',$data);
    }
    public function addUser(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'name' => 'required|string',
                'phone' => 'required|numeric|digits:10',
                'email' => 'required|email',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','message' => $errors], 200);
        }
        $name = $res->name;
        $email = $res->email;
        $phone = $res->phone;
        $role = $res->role;
        $package = $res->pkg;
        #check mobile is exist or not
        $checkPhone = User::where('phone',$phone)->first();
        $checkRegPhone = DB::table('register')->where('mobile',$phone)->first();
        if($checkPhone != "" || $checkPhone != null || $checkRegPhone != "" || $checkRegPhone != null)
        return response()->json(['status'=>'ERROR','message'=>'Phone number already exist']);
        $checkEmail = User::where('email',$email)->first();
        $checkRegEmail = DB::table('register')->where('email',$email)->first();
        if($checkEmail != "" || $checkEmail != null || $checkRegEmail != "" || $checkRegEmail != null)
        return response()->json(['status'=>'ERROR','message'=>'Email Id already exist']);
        $username = 'ECZ'.rand(100000000,9999999999);
        $pin = rand(1000,9999);
        $password = rand(100000,999999);
        $insertData = array(
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'role' => $role,
            'username' => $username,
            'pin' => $pin,
            'password' => $password,
            'package' => $package
            );
        #sending onboard email
        $companyData = $this->common();
        $data = [
            'name' => $name,
            'company' =>$companyData['title'],
            'logo' =>$companyData['logo'],
            'username' => $username,
            'pin' => $pin,
            'password' => $password,
            'view' => 'mail.onboard',
            'subject' => 'User onboard credentials',
            'support' => DB::table('settings')->where('name','cemail')->first()->value,
            'loginUrl' => env('APP_URL'),
            ];
        Mail::to($email)->send(new SendEmail($data));
        $user = new User();
        $user->fill($insertData);
        $res = $user->save();
        if($res)
        {
            return response()->json(['status'=>'SUCCESS','message'=>'User registered successfully']);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>'Some unknown error occoured']);
        }
    }
    public function userRequest()
    {
        $data = $this->common();
        $data['tname'] = 'User Request';
        $data['request'] = DB::table('register')->join('role', 'register.role', '=', 'role.id')->select('register.*', 'role.name as roleName')->get();
        $data['package'] = DB::table('package')->select('name','id','role')->get();
        return view('admin.user.request',$data);
    }
    public function approveUser(Request $res)
    {
        $id = $res->id;
        $requestRow = DB::table('register')->where('id',$id)->first();
        if($requestRow == "" || $requestRow == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid request!!']);
        $name = $requestRow->first.' '.$requestRow->last;
        $email = $requestRow->email;
        $phone = $requestRow->mobile;
        $role = $requestRow->role;
        $owner = $requestRow->ref_by;
        $package = $res->package;
        $checkPhone = User::where('phone',$phone)->first();
        if($checkPhone != "" || $checkPhone != null)
        return response()->json(['status'=>'ERROR','message'=>'Phone number already exist']);
        $checkEmail = User::where('email',$email)->first();
        if($checkEmail != "" || $checkEmail != null)
        return response()->json(['status'=>'ERROR','message'=>'Email Id already exist']);
        $username = 'ECZ'.rand(100000000,9999999999);
        $pin = rand(1000,9999);
        $password = rand(100000,999999);
        $insertData = array(
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'role' => $role,
            'username' => $username,
            'pin' => $pin,
            'owner' => $owner,
            'password' => $password,
            'package' => $package
            );
        #sending onboard email
        $companyData = $this->common();
        $data = [
            'name' => $name,
            'company' =>$companyData['title'],
            'logo' =>$companyData['logo'],
            'username' => $username,
            'pin' => $pin,
            'password' => $password,
            'view' => 'mail.onboard',
            'subject' => 'User onboard credentials',
            'support' => DB::table('settings')->where('name','cemail')->first()->value,
            'loginUrl' => env('APP_URL'),
            ];
        Mail::to($email)->send(new SendEmail($data));
        $user = new User();
        $user->fill($insertData);
        $res = $user->save();
        if($res)
        {
            DB::table('register')->where('id', $id)->delete();
            return response()->json(['status'=>'SUCCESS','message'=>'User registered successfully']);
        }
        else
        {
            return response()->json(['status'=>'ERROR','message'=>'Some unknown error occoured']);
        }
    }
    public function rejectUser($id)
    {
        $requestRow = DB::table('register')->select('email','first')->where('id',$id)->first();
        $requestRow = DB::table('register')->where('id',$id)->first();
        if($requestRow == "" || $requestRow == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid request!!']);
        $name = $requestRow->first;
        $email = $requestRow->email;
        $companyData = $this->common();
        $data = [
            'name' => $name,
            'company' =>$companyData['title'],
            'logo' =>$companyData['logo'],
            'view' => 'mail.reject',
            'subject' => 'User Request Rejected',
            'support' => DB::table('settings')->where('name','cemail')->first()->value,
            ];
        Mail::to($email)->send(new SendEmail($data));
        DB::table('register')->where('id', $id)->delete();
        return response()->json(['status'=>'SUCCESS','message'=>'Request rejected successfully']);
    }
    public function loginLogs($type)
    {
        $blockedUsers = ['user', 'admin'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $data = $this->common();
        $data['tname'] = 'Login Logs';
        $data['logs'] = DB::table('loginlog')->where('type',strtoupper($type))->get();
        return view('admin.loginLogs',$data);
    }
    public function userTickets($type)
    {
        $blockedUsers = ['pending', 'all'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $data = $this->common();
        $data['tname'] = 'User support tickets';
        if($type == 'all')
        $data['tickets'] = User::join('ticket', 'users.id', '=', 'ticket.uid')->get(['ticket.*', 'users.name as name','users.username']);
        else
        $data['tickets'] = User::join('ticket', 'users.id', '=', 'ticket.uid')->where('ticket.status', '!=', 'CLOSED')->get(['ticket.*', 'users.name as name','users.username']);
        return view('admin.userTickets',$data);
    }
    
    public function closeTicket(Request $res)
    {
        $id = $res->id;
        $reply = $res->msg;
        $getTicket = DB::table('ticket')->select('uid','service','ticketid')->where('id',$res->id)->get();
        $user = User::select('email','name')->where('id',$getTicket[0]->uid)->get();
        DB::table('ticket')->where('id',$id)->update(['adminmsg'=>$reply,'status'=>'CLOSED']);
        $companyData = $this->common();
        $data = [
            'name' => $user[0]->name,
            'ticketId' => $getTicket[0]->ticketid,
            'type' => $getTicket[0]->service,
            'msg' => $reply,
            'company' =>$companyData['title'],
            'logo' =>$companyData['logo'],
            'view' => 'mail.supportTicket',
            'subject' => 'Ticket has closed',
            'support' => DB::table('settings')->where('name','cemail')->first()->value,
            ];
        Mail::to($user[0]->email)->send(new SendEmail($data));
        return response()->json(['status'=>'SUCCESS','message'=>'Ticket has been closed successfully']);
    }
    public function pendingKyc($type)
    {
        $blockedUsers = ['pending', 'all'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $data = $this->common();
        if($type == 'pending')
        {
            $data['tname'] = 'Pending Profile KYC';
            $data['pendingKyc'] = DB::table('kyc')->join('users', 'kyc.uid', '=', 'users.id')->leftJoin('icicstate', 'kyc.state', '=', 'icicstate.id')->select('kyc.*', 'users.name as user_name', 'users.phone', 'icicstate.name as stateName')->where('kyc.active','!=',1)->get();
            return view('admin.user.profileKyc',$data);
        }
        else
        {
            $data['tname'] = 'All Profile KYC';
            $data['pendingKyc'] = DB::table('kyc')->join('users', 'kyc.uid', '=', 'users.id')->leftJoin('icicstate', 'kyc.state', '=', 'icicstate.id')->select('kyc.*', 'users.name as user_name', 'users.phone', 'icicstate.name as stateName')->get();
            return view('admin.user.profileKyc',$data);
        }
    }
    public function approveKyc($id)
    {
        $checkKyc = DB::table('kyc')->where('id',$id)->count();
        if($checkKyc != 1)
        return response()->json(['status'=>'ERROR','message'=>'Kyc data not found!']);
        $this->updateData('kyc','id',$id,['active'=>1]);
        $getRow = DB::table('kyc')->select('uid')->where('id',$id)->get();
        $user = User::select('email','name')->where('id',$getRow[0]->uid)->get();
        $companyData = $this->common();
        $data = [
            'name' => $user[0]->name,
            'company' =>$companyData['title'],
            'logo' =>$companyData['logo'],
            'view' => 'mail.approveKyc',
            'subject' => 'Ticket has closed',
            'support' => DB::table('settings')->where('name','cemail')->first()->value,
            ];
        Mail::to($user[0]->email)->send(new SendEmail($data));
        return response()->json(['status'=>'SUCCESS','message'=>'Kyc has been approved successfully']);
        
    }
    public function rejectKyc($id)
    {
        $checkKyc = DB::table('kyc')->where('id',$id)->count();
        if($checkKyc != 1)
        return response()->json(['status'=>'ERROR','message'=>'Kyc data not found!']);
        $getRow = DB::table('kyc')->select('uid')->where('id',$id)->get();
        $user = User::select('email','name')->where('id',$getRow[0]->uid)->get();
        DB::table('kyc')->where('id', '=', $id)->delete();
        $companyData = $this->common();
        $data = [
            'name' => $user[0]->name,
            'company' =>$companyData['title'],
            'logo' =>$companyData['logo'],
            'view' => 'mail.rejectKyc',
            'subject' => 'KYC has been rejected',
            'support' => DB::table('settings')->where('name','cemail')->first()->value,
            ];
        Mail::to($user[0]->email)->send(new SendEmail($data));
        return response()->json(['status'=>'SUCCESS','message'=>'Kyc has been rejected']);
    }
    public function aepsKyc()
    {
        $data = $this->common();
        $data['tname'] = 'Aeps Kyc';
        $data['aepsKyc'] = DB::table('aepskyc')->join('users', 'aepskyc.uid', '=', 'users.id')->leftJoin('kyc', 'kyc.uid', '=', 'aepskyc.uid')->leftJoin('icicstate', 'kyc.state', '=', 'icicstate.id')->select('kyc.*', 'users.name as user_name', 'users.phone','aepskyc.outlet','aepskyc.status as kyc_status','icicstate.name as stateName')->get();
        return view('admin.user.aepsKyc',$data);
    }
    public function aepsKycdelete($id)
    {
        $checkKyc = DB::table('aepskyc')->where('id',$id)->count();
        if($checkKyc != 1)
        return response()->json(['status'=>'ERROR','message'=>'AEPS Kyc data not found!']);
        $getRow = DB::table('aepskyc')->select('uid')->where('id',$id)->get();
        $user = User::select('email','name')->where('id',$getRow[0]->uid)->get();
        DB::table('kyc')->where('id', '=', $id)->delete();
        $companyData = $this->common();
        $data = [
            'name' => $user[0]->name,
            'company' =>$companyData['title'],
            'logo' =>$companyData['logo'],
            'view' => 'mail.rejectKyc',
            'subject' => 'AEPS kyc deleted',
            'support' => DB::table('settings')->where('name','cemail')->first()->value,
            ];
        Mail::to($user[0]->email)->send(new SendEmail($data));
        return response()->json(['status'=>'SUCCESS','message'=>'Kyc has been rejected']);
    }
    public function accountApproval($type)
    {
        $blockedUsers = ['pending', 'all'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $data = $this->common();
        if($type == 'pending')
        {
            $data['tname'] = 'Pending Payout Account';
            $data['accounts'] = User::join('payoutaccount','payoutaccount.uid', '=', 'users.id')->where('payoutaccount.status', '=', 'PENDING')->get(['payoutaccount.*', 'users.name as user_name','users.username']);
        return view('admin.user.payoutAccount',$data);
        }
        $data['tname'] = 'All Payout Account';
        $data['accounts'] = User::join('payoutaccount','payoutaccount.uid', '=', 'users.id')->get(['payoutaccount.*', 'users.name as user_name','users.phone','users.username']);
        return view('admin.user.payoutAccount',$data);
    }
    public function approveAccount($id)
    {
        $checkAccount = DB::table('payoutaccount')->where('id',$id)->count();
        if($checkAccount != 1)
        return response()->json(['status'=>'ERROR','message'=>'Account not found!']);
        DB::table('payoutaccount')->where('id',$id)->update(['status'=>'APPROVED']);
        return response()->json(['status'=>'SUCCESS','message'=>'Payout account approved successfully']);
    }
    public function rejectAccount($id)
    {
        $checkAccount = DB::table('payoutaccount')->where('id',$id)->count();
        if($checkAccount != 1)
        return response()->json(['status'=>'ERROR','message'=>'Account not found!']);
        DB::table('payoutaccount')->where('id',$id)->delete();
        return response()->json(['status'=>'SUCCESS','message'=>'Payout account rejected successfully']);
    }
    public function walletHistory()
    {
        $data = $this->common();
        $data['tname'] = 'Pending Payout Account';
        $data['walletHistory'] = User::join('wallet','users.id' , '=', 'wallet.uid')->get(['wallet.*','users.name','users.username','users.phone']);
        return view('admin.wallet.history',$data);
    }
    public function userWallet($type)
    {
        $blockedUsers = ['credit', 'debit'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $data = $this->common();
        $data['tname'] = $type.' wallet';
        $data['type'] = $type;
        $data['users'] = User::select('name','username','phone','email','wallet','id')->get();
        return view('admin.wallet.fund',$data);
    }
    public function walletActivity(Request $res,$type)
    {
        $blockedUsers = ['credit', 'debit'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $uid = $res->id;
        $amount = $res->amount;
        if($type == 'credit')
        {
            $wallet = User::select('wallet')->find($uid);
            $thxid = 'WC'.rand(1000000000,9999999999);
            $this->creditEntry($uid,'WALLET-CREDIT',$thxid,$amount);
            return response()->json(['status'=>'SUCCESS','message'=>'Amount has been credited successfully']);
        }
        else
        {
            $wallet = User::select('wallet')->find($uid);
            $wallet = $wallet->wallet;
            if($wallet < $amount)
            return response()->json(['status'=>'ERROR','message'=>'Insufficient fund in user wallet']);
            $thxid = 'WD'.rand(1000000000,9999999999);
            $this->debitEntry($uid,'WALLET-DEBIT',$thxid,$amount);
            return response()->json(['status'=>'SUCCESS','message'=>'Amount has been debited successfully']);
        }
    }
    public function walletTopup($type)
    {
        $blockedUsers = ['pending', 'all'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $data = $this->common();
        $data['tname'] = $type.' Topup request';
        if($type == 'pending')
        $data['topups'] = User::join('topup','topup.uid','=','users.id')->leftJoin('bank','bank.id','=','topup.bank')->where('topup.status','=','PENDING')->select('topup.*','users.name','users.username','users.phone','bank.account','bank.ifsc','bank.bank')->get();
        else
        $data['topups'] = User::join('topup','topup.uid','=','users.id')->leftJoin('bank','bank.id','=','topup.bank')->select('topup.*','users.name','users.username','users.phone','bank.account','bank.ifsc','bank.bank')->get();
        return view('admin.wallet.topup',$data);
    }
    public function topupAction($type,$id)
    {
        $blockedUsers = ['approve', 'reject'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        #check topup row
        $checkRow = DB::table('topup')->where('id',$id)->count();
        if($checkRow != 1)
        return response()->json(['status'=>'ERROR','message'=>'Invalid transaction']);
        if($type == 'approve')
        {
            $checkRow = DB::table('topup')->where('id',$id)->first();
            DB::table('topup')->where('id',$id)->update(['status'=>'APPROVED']);
            $this->creditEntry($checkRow->uid,'TOPUP',$checkRow->txnid,$checkRow->amount);
            return response()->json(['status'=>'SUCCESS','message'=>'Wallet has been credited successfully']);
        }
        else
        {
            $checkRow = DB::table('topup')->where('id',$id)->first();
            DB::table('topup')->where('id',$id)->update(['status'=>'REJETED']);
            return response()->json(['status'=>'SUCCESS','message'=>'Topup rejected successfully']);
        }
    }
    public function adminFetchtransaction($type)
    {
        $blockedUsers = ['wallet', 'aeps','bbps','recharge','dmt','payout','qtransfer','uti','wallet-to-wallet','payoutcall'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $data = $this->common();
        $data['tname'] = $type.' Transaction History';
        $data['type'] = $type;
        return view('admin.transactions.transaction',$data);
    }
    public function adminGettransaction(Request $res,$type)
    {
        $blockedUsers = ['wallet', 'aeps','bbps','recharge','dmt','payout','qtransfer','uti','wallet-to-wallet','payoutcall'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        $from = date('Y-m-d H:i:s', strtotime(Carbon::parse($res->from)->startOfDay()));
        $to = date('Y-m-d H:i:s', strtotime(Carbon::parse($res->to)->endOfDay()));
        $amountTo = $res->amountTo;
        $amountFrom = $res->amountFrom;
        $orderType = $res->orderType;
        $txnType = $res->txnType;
        if($orderType == 1)
        $orderType = 'desc';
        else
        $orderType = 'asc';
        switch($type)
        {
            case 'aeps' :
                $txnD = User::join('iaepstxn','iaepstxn.uid','=','users.id')->leftJoin('banks','banks.id','=','iaepstxn.bank')->whereBetween('iaepstxn.date', [$from, $to])->orderBy('iaepstxn.id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('iaepstxn.status', $txnType);
                }
                $txnData = $txnD->select('iaepstxn.*','users.username','banks.name as bank_name')->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                foreach ($txnData as $tData)
                {
                    if($tData->type == 'CW' || $tData->type == 'AP')
                    $tData->amount = $tData->txnamount;
                }
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.aeps',['txnData'=>$txnData])->render()]);
                break;
            case 'dmt' :
                $txnD = User::join('dmrtxn','dmrtxn.uid','=','users.id')->whereBetween('dmrtxn.date', [$from, $to])->orderBy('dmrtxn.id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('dmrtxn.status', $txnType);
                }
                $txnData = $txnD->select('dmrtxn.*','users.username')->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.dmt',['txnData'=>$txnData])->render()]);
                break;
            case 'qtransfer' :
                $txnD = User::join('qtransfertxn','qtransfertxn.uid','=','users.id')->whereBetween('qtransfertxn.date', [$from, $to])->orderBy('qtransfertxn.id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('qtransfertxn.status', $txnType);
                }
                $txnData = $txnD->select('qtransfertxn.*','users.username')->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.qtransfer',['txnData'=>$txnData])->render()]);
                break;
            case 'payout' :
                $txnD = User::join('payouttxn','payouttxn.uid','=','users.id')->whereBetween('payouttxn.date', [$from, $to])->orderBy('payouttxn.id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('payouttxn.status', $txnType);
                }
                $txnData = $txnD->select('payouttxn.*','users.username')->get();
                
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.payout',['txnData'=>$txnData])->render()]);
                break;
            case 'payoutcall' :
                $txnData = User::join('payouttxn','payouttxn.uid','=','users.id')->where('payouttxn.status','PENDING')->whereBetween('payouttxn.date', [$from, $to])->orderBy('payouttxn.id', $orderType)->select('payouttxn.*','users.username')->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.payoutcall',['txnData'=>$txnData])->render()]);
                break;
            case 'recharge' :
                $txnD = User::join('rechargetxn','rechargetxn.uid','=','users.id')->leftJoin('rechargeop','rechargeop.id','=','rechargetxn.operator')->whereBetween('rechargetxn.date', [$from, $to])->orderBy('rechargetxn.id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('rechargetxn.status', $txnType);
                }
                $txnData = $txnD->select('rechargetxn.*','users.username','rechargeop.name as operator_name')->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.recharge',['txnData'=>$txnData])->render()]);
                break;
            case 'bbps' :
                $txnD = User::join('bbpstxn','bbpstxn.uid','=','users.id')->leftJoin('bbpsnewop','bbpstxn.boperator','=','bbpsnewop.id')->whereBetween('bbpstxn.date', [$from, $to])->orderBy('bbpstxn.id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('bbpstxn.status', $txnType);
                }
                $txnData = $txnD->select('bbpstxn.*','users.username','bbpsnewop.name as operator_name')->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.bbps',['txnData'=>$txnData])->render()]);
                break;
            case 'uti' :
                $txnD = User::join('pancoupontxn','pancoupontxn.uid','=','users.id')->whereBetween('pancoupontxn.date', [$from, $to])->orderBy('pancoupontxn.id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('pancoupontxn.status', $txnType);
                }
                $txnData = $txnD->select('pancoupontxn.*','users.username')->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.uti',['txnData'=>$txnData])->render()]);
                break;
            case 'wallet' :
                $txnD = User::join('wallet','wallet.uid','=','users.id')->whereBetween('wallet.date', [$from, $to])->orderBy('wallet.id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('wallet.txntype', $txnType);
                }
                $txnData = $txnD->select('wallet.*','users.username')->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.wallet',['txnData'=>$txnData])->render()]);
                break;
            case 'wallet-to-wallet' :
                $txnD = DB::table('wallet_to_wallet')->whereBetween('date', [$from, $to])->orderBy('id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('type', $txnType);
                }
                $txnData = $txnD->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                $txns = array();
                foreach ($txnData as $txn)
                {
                    $sender = User::select('name','phone','username')->where('id',$txn->sender)->first();
                    $receiver = User::select('name','phone','username')->where('id',$txn->receiver)->first();
                    $latestTxns = array(
                        'senderUsername' => $sender->username,
                        'senderName' => $sender->name,
                        'senderPhone' => $sender->phone,
                        'receiverUsername' => $receiver->username,
                        'receiverName' => $receiver->name,
                        'receiverphone' => $receiver->phone,
                        'type' => $txn->type,
                        'amount' => $txn->amount,
                        'date' => $txn->date,
                        'txnid' => $txn->txnid
                        );
                    $txns[] = $latestTxns;
                }
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('admin.transactions.wallettowallet',['txnData'=>$txns])->render()]);
                break;
            default:
                return view('errors.404');
        }
        return $txnData;
    }
    public function managePackage()
    {
        $data = $this->common();
        $data['tname'] = 'Manage Package';
        $data['packages'] = DB::table('package')->join('role', 'package.role', '=', 'role.id')->select('package.*', 'role.name as roleName','role.id as roleId')->get();
        $data['roles'] = DB::table('role')->select('id','name')->get();
        return view('admin.package',$data);
    }
    public function packageAction(Request $res,$type,$id)
    {
        $blockedUsers = ['update', 'delete'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        #check topup row
        $checkRow = DB::table('package')->where('id',$id)->count();
        if($checkRow != 1)
        return response()->json(['status'=>'ERROR','message'=>'Invalid package']);
        if($type == 'update')
        {
            $newName = $res->name;
            DB::table('package')->where('id',$id)->update(['name'=>$newName]);
            return response()->json(['status'=>'SUCCESS','message'=>'Package name updated successfully']);
        }
        else
        {
            #check package already in use or not
            $checkUse = User::where('package',$id)->count();
            if($checkUse > 0)
            return response()->json(['status'=>'ERROR','message'=>'Package already in use cant delete it!!']);
            #delete package from all table 
            $tables = DB::table('commtables')->get();
            foreach ($tables as $table)
            {
                DB::table($table->table_name)->where('package',$id)->delete();
            }
            DB::table('package')->where('id',$id)->delete();
            return response()->json(['status'=>'SUCCESS','message'=>'Package deleted successfully']);
        }
    }
    public function createPackage(Request $res)
    {
        $validator = Validator::make($res->all(), [
                'package' => 'required|string',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','message' => $errors], 200);
        }
        $package = $res->package;
        $role = $res->role;
        #check package already exist or not
        $checkPackage = DB::table('package')->where('name',$package)->count();
        if($checkPackage > 0)
        return response()->json(['status'=>'ERROR','message'=>'Package with same name already exists!']);
        #create package 
        $insertData = array(
            'name' => $package,
            'role' => $role
            );
        $packageId = DB::table('package')->insertGetId($insertData);
        #create package to all comm and charge table
        $tables = DB::table('commtables')->get();
        foreach ($tables as $table)
        {
            if($table->bracket == 0)
            {
                if($table->table_name == 'bbps')
                {
                    $getallOperator = DB::table('bbpsincat')->select('id','cat_key')->get();
                    foreach ($getallOperator as $op)
                    {
                        if($op->id == 18)
                        {
                            for($i=0;$i<2;$i++)
                            {
                                $data = array(
                                    'opid' =>$op->id,
                                    'amount' =>0,
                                    'percent' => 0,
                                    'package' =>$packageId,
                                    );
                                DB::table('bbps')->insert($data);
                            }
                        }
                        else
                        {
                            $data = array(
                                    'opid' =>$op->id,
                                    'amount' =>0,
                                    'percent' => 0,
                                    'package' =>$packageId,
                                    );
                                DB::table('bbps')->insert($data);
                        }
                    }
                }
                elseif($table->table_name == 'comissionv2')
                {
                    $getallOperator = DB::table('rechargeop')->select('id','name')->get();
                    foreach ($getallOperator as $op)
                    {
                        $data = array(
                            'operator' =>$op->id,
                            'amount' =>0,
                            'percent' => 0,
                            'package' => 0,
                            'package' => $packageId
                            );
                        DB::table('comissionv2')->insert($data);
                    }
                    
                }
                else
                {
                    $data = array(
                        'package' => $packageId,
                        'amount' => 0,
                        'percent' => 0
                        );
                    DB::table($table->table_name)->insert($data);
                }
            }
            else
            {
                for($i=0;$i<10;$i++)
                {
                    $data = array(
                        'froma' =>0,
                        'toa' => 0,
                        'amount' => 0,
                        'percent' => 0,
                        'package' => $packageId
                        );
                    DB::table($table->table_name)->insert($data);
                }
            }
        }
        return response()->json(['status'=>'SUCCESS','message'=>'Package created successfully!!']); 
    }
    public function packageCommission()
    {
        $data = $this->common();
        $data['tname'] = 'Commission and Charges';
        $data['labeles'] = DB::table('commtables')->select('id','label')->get();
        $data['packages'] = DB::table('package')->select('name','id')->get();
        return view('admin.package.packageComm',$data);
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
            'previlage' => 'admin',
            'table' => $tableData->table_name,
            'package' => $package,
            'column' => $newColumn
            );
        return response()->json(['status'=>'SUCCESS','view'=>view('admin.package.fetch',$data)->render()]);
        return view('admin.package.fetch',$data);
        
    }
    public function saveCommCharge(Request $res)
    {
        $table = $res->table;
        $package = $res->package;
        $tableData = DB::table($table)->where('package',$package)->get();
        $i=1;
        if (Schema::hasTable($table)) {
            $columns = Schema::getColumnListing($table);
        }
        $elementsToRemove = ["id", "commission", "package"];
        $newColumn = array_diff($columns,$elementsToRemove);
        $newColumn = array_values($newColumn);
        // return $newColumn;
        if($res->bracket == 1)
        {
            foreach ($tableData as $data)
            {
                $upData = array(
                    $newColumn[0] => $_POST[$newColumn[0].$i] != ""?$_POST[$newColumn[0].$i]:0,
                    $newColumn[1] => $_POST[$newColumn[1].$i] != ""?$_POST[$newColumn[1].$i]:0,
                    $newColumn[2] => $_POST[$newColumn[2].$i] != ""?$_POST[$newColumn[2].$i]:0,
                    $newColumn[3] => isset($_POST[$newColumn[3].$i])?$_POST[$newColumn[3].$i]:0,
                    );
                $upData = array_filter($upData, function($value, $key) {
                    return $key !== "";
                }, ARRAY_FILTER_USE_BOTH);
                // return $upData;
                DB::table($table)->where('id',$data->id)->update($upData);
                $i++;
            }
        }
        else
        {
            if($table == 'comissionv2' || $table == 'bbps')
            {
                foreach ($tableData as $data)
                {
                    if($table == 'comissionv2')
                    $opid = DB::table('rechargeop')->where('name',$_POST[$newColumn[0].$i??'']??0)->first()->id;
                    else
                    $opid = DB::table('bbpsincat')->where('name',$_POST[$newColumn[0].$i??'']??0)->first()->id;
                    $upData = array(
                        $newColumn[0]??'' => $opid,
                        $newColumn[1]??'' => $_POST[$newColumn[1].$i??'']??0,
                        $newColumn[2]??'' => $_POST[$newColumn[2].$i??'']??0,
                        );
                    $upData = array_filter($upData, function($value, $key) {
                        return $key !== "";
                    }, ARRAY_FILTER_USE_BOTH);
                    // print_r($upData);
                    DB::table($table)->where('id',$data->id)->update($upData);
                    $i++;
                }
            }
            else
            {
                foreach ($tableData as $data)
                {
                    $upData = array(
                        $newColumn[0]??'' => $_POST[$newColumn[0].$i??'']??0,
                        $newColumn[1]??'' => $_POST[$newColumn[1].$i??'']??0,
                        );
                    $upData = array_filter($upData, function($value, $key) {
                        return $key !== "";
                    }, ARRAY_FILTER_USE_BOTH);
                    DB::table($table)->where('id',$data->id)->update($upData);
                    $i++;
                }
            }
        }
        return response()->json(['status'=>'SUCCESS','message'=>'Data updated successfully']);
    }
    public function manageSettings()
    {
        $data = $this->common();
        $data['tname'] = 'Manage Setting';
        $data['settingData'] = DB::table('settings')->select('id','name','value','type')->get();
        return view('admin.mainSeting',$data);
    }
    public function submitSetting(Request $res)
    {
        $fieldType = $res->fieldType;
        $fieldName = $res->fieldName;
        $field = $res->$fieldName;
        // return $fieldName;
        if($fieldType == 'file')
        {
            $fileSize = $_FILES[$fieldName]['size'];
            if($fileSize == 0)
            {
                $file = Setting::where('name',$fieldName)->first()->value;
            }
            else
            {
                 $file = $this->upliadFile($res->file($fieldName));
            }
            $this->updateData('settings','name',$fieldName,['value'=>$file]);
            return response()->json(['status'=>'SUCCESS','message'=>$fieldName.' Updated successfully']);
            
        }
        else
        {
            $this->updateData('settings','name',$fieldName,['value'=>$field]);
            return response()->json(['status'=>'SUCCESS','message'=>$fieldName.' Updated successfully']);
        }
    }
    public function addCompanyAccount()
    {
        $data = $this->common();
        $data['tname'] = 'Add company Account';
        $data['bankData'] = DB::table('bank')->get();
        return view('admin.addCompanyAccount',$data);
    }
    public function addBankAccount(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'account' => 'required|numeric',
            'ifsc' => 'required|string',
            'bank' => 'required|string',
            'name' => 'required|string',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','message' => $errors], 200);
        }
        $account = $res->account;
        $ifsc = $res->ifsc;
        $bank = $res->bank;
        $name = $res->name;
        #check account already exist or not
        $checkAccount = DB::table('bank')->where('account',$account)->count();
        if($checkAccount > 0)
        return response()->json(['status'=>'ERROR','message'=>'Account number already added!!']);
        $insertData = array(
            'account' => $account,
            'ifsc' => $ifsc,
            'bank' => $bank,
            'name' => $name,
            'bname' => $name,
            );
        $res = DB::table('bank')->insert($insertData);
        if($res)
        return response()->json(['status'=>'SUCCESS','message'=>'Bank account added successfully']);
        else
        return response()->json(['status'=>'ERROR','message'=>'Something went wrong']);
    }
    public function manageService()
    {
        $data = $this->common();
        $data['tname'] = 'Manage all service';
        $data['serviceData'] = DB::table('service')->where('id',1)->first();
        return view('admin.manageService',$data);
    }
    public function saveServiceChanges(Request $res)
    {
        $aeps = isset($res->aeps)?$res->aeps:0;
        $bbps = isset($res->bbps)?$res->bbps:0;
        $dmt = isset($res->dmt)?$res->dmt:0;
        $payout = isset($res->payout)?$res->payout:0;
        $qtransfer = isset($res->qtransfer)?$res->qtransfer:0;
        $recharge = isset($res->recharge)?$res->recharge:0;
        $uti = isset($res->uti)?$res->uti:0;
        $data= array(
            'aeps' => $aeps,
            'bbps' => $bbps,
            'recharge' => $recharge,
            'dmt' => $dmt,
            'payout' => $payout,
            'qtransfer' => $qtransfer,
            'uti' => $uti
            );
        $result = DB::table('service')->where('id','1')->update($data);
        if($result)
        return response()->json(['status'=>'SUCCESS','message'=>'Update successfully']);
        else
        return response()->json(['status'=>'ERROR','message'=>'Something went wrong']);
    }
    public function adminDeleteBank(Request $res, $type, $id)
    {
        $blockedUsers = ['delete', 'update'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        #check id is present or not
        $checkAccount = DB::table('bank')->where('id',$id)->count();
        if($checkAccount <= 0)
        return response()->json(['status'=>'ERROR','message'=>'Account not found']);
        if($type == 'delete')
        {
            $res = DB::table('bank')->where('id',$id)->delete();
            if($res)
            return response()->json(['status'=>'SUCCESS','message'=>'Bank account deleted successfully']);
            return response()->json(['status'=>'ERROR','message'=>'Unknown error occoured']);
        }
        else
        {
            $validator = Validator::make($res->all(), [
                'account' => 'required|numeric',
                'name' => 'required|string',
                'ifsc' => 'required|string',
                'bank' => 'required|string',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->first();
                return response()->json(['status'=>'ERROR','message' => $errors], 200);
            }
            $account = $res->account;
            $name = $res->name;
            $ifsc = $res->ifsc;
            $bank = $res->bank;
            $data = array(
                'name' => $name,
                'bname' => $name,
                'account' => $account,
                'ifsc' => $ifsc,
                'bank' => $bank,
                );
            $res = DB::table('bank')->where('id',$id)->update($data);
            if($res)
            return response()->json(['status'=>'SUCCESS','message'=>'Bank details updated successfylly']);
            return response()->json(['status'=>'ERROR','message'=>'Unknown error occoured']);
        }
    }
    public function adminOtherServices($type)
    {
        $blockedUsers = ['view-all', 'add'];
         if (!in_array($type, $blockedUsers)) {
             return view('errors.admin404');
        }
        if($type == 'add')
        {
            $data = $this->common();
            $data['tname'] = 'Add other services';
            return view('admin.manage.addOtherservice',$data);
        }
        else
        {
            $data = $this->common();
            $data['tname'] = 'Other Service List';
            $data['otherService'] = DB::table('otherservices')->get();
            return view('admin.manage.listOtherservice',$data);
        }
    }
    public function adminAddOtherservice(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'name' => 'required|string',
            'url' => 'required|url',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','message' => $errors], 200);
        }
        $name = $res->name;
        $url = $res->url;
        $insertData = array(
            'name' => $name,
            'link' => $url,
            );
        $res = DB::table('otherservices')->insert($insertData);
        if($res)
        return response()->json(['status'=>'SUCCESS','message'=>'Service added successfully']);
        return response()->json(['status'=>'ERROR','message'=>'Unknown error occoured']);
    }
    public function adminDeleteOtherservice($id)
    {
        #check id is present or not
        $checkAccount = DB::table('otherservices')->where('id',$id)->count();
        if($checkAccount <= 0)
        return response()->json(['status'=>'ERROR','message'=>'Service not found']);
        $res = DB::table('otherservices')->where('id',$id)->delete();
        if($res)
        return response()->json(['status'=>'SUCCESS','message'=>'Service deleted successfully']);
        return response()->json(['status'=>'ERROR','message'=>'Unknown error occoured']);
    }
    public function profileSettings()
    {
        $data = $this->common();
        $data['tname'] = 'Profile Settings';
        return view('admin.profileSettings',$data);
    }
    public function resetPassword(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'createPassword' => 'required|string',
            'oldPassword' => 'required|string',
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['status'=>'ERROR','message' => $errors], 200);
        }
        $oldPassword = $res->oldPassword;
        $createPassword = $res->createPassword;
        #check Old password
        $checkPassword = AdminModel::find(session('aid'))->password;
        if($oldPassword != $checkPassword)
        return response()->json(['status'=>'ERROR','message'=>'Invalid old password!']);
        $res = AdminModel::where('id',session('aid'))->update(['password'=>$createPassword]);
        if($res)
        return response()->json(['status'=>'SUCCESS','message'=>'Password Reset successfully']);
        return response()->json(['status'=>'ERROR','message'=>'Some errors occoured!!']);
    }
    public function updateUserService(Request $res)
    {
        $aeps = isset($res->aeps)?$res->aeps:0;
        $bbps = isset($res->bbps)?$res->bbps:0;
        $dmt = isset($res->dmt)?$res->dmt:0;
        $payout = isset($res->payout)?$res->payout:0;
        $qtransfer = isset($res->qtransfer)?$res->qtransfer:0;
        $recharge = isset($res->recharge)?$res->recharge:0;
        $uti = isset($res->uti)?$res->uti:0;
        $data= array(
            'aeps' => $aeps,
            'bbps' => $bbps,
            'recharge' => $recharge,
            'dmt' => $dmt,
            'payout' => $payout,
            'qtransfer' => $qtransfer,
            'uti' => $uti
            );
        $uid = $res->uid;
        $res = User::where('id',$uid)->update($data);
        if($res)
        return response()->json(['status'=>'SUCCESS','message'=>'Services updated successfully']);
        return response()->json(['status'=>'ERROR','message'=>'Some errors occoured!!']);
    }
    public function adminUserEdit($id)
    {
        $data = ['id'=>$id];
        $validator = Validator::make($data, [
            'id' => 'exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERROR','message'=>$validator->errors()->first()]);
        }
        $user = User::find($id);
        return response()->json(['status'=>'SUCCESS','view'=>view('admin.user.editUser',$user)->render()]);
    }
    public function adminUpdateUserDetails(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric|digits:10',
            'uid' =>    'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERROR','message'=>$validator->errors()->first()]);
        }
        $data = array(
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone
            );
        $res = User::where('id',$req->uid)->update($data);
        if($res)
        return response()->json(['status'=>'SUCCESS','message'=>'User updated successfully']);
        return response()->json(['status'=>'ERROR','message'=>'Some errors occoured!!']);
    }
    public function upgradeMember()
    {
        $data = $this->common();
        $data['tname'] = 'Upgrade Member';
        $data['userDetails'] = User::join('role','role.id','=','users.role')->leftJoin('package','package.id','=','users.package')->select('users.id as userId','users.name as userName','role.name as roleName','package.name as Package')->get();
        $data['allPackage'] = DB::table('package')->get();
        $data['allRole'] = DB::table('role')->get();
        return view('admin.user.upgradeMember',$data);
    }
    public function submitUpgradeMember(Request $res,$type)
    {
        $blockedUsers = ['package', 'role'];
         if (!in_array($type, $blockedUsers)) {
             abort(404);
        }
        $user = $res->user;
        if($type == 'package')
        {
            $package = $res->data;
            try {
			User::where('id', $user)->update([
				'package' => $package
			]);
			return response()->json(['status' => 'SUCCESS', 'message' => 'Package upgraded successfully']);
    		} catch (\Illuminate\Database\QueryException $e) {
    			return response()->json(['status' => 'ERROR', 'message' => 'Unknown error occured'], 500);
    		}
        }
        else
        {
            $role = $res->data;
            $package = DB::table('package')->where('role',$role)->first()->id;
            try {
			User::where('id', $user)->update([
			    'role' => $role,
				'package' => $package
			]);
			return response()->json(['status' => 'SUCCESS', 'message' => 'Package upgraded successfully']);
    		} catch (\Illuminate\Database\QueryException $e) {
    			return response()->json(['status' => 'ERROR', 'message' => 'Unknown error occured'], 500);
    		}
        }
    }
    public function lockAmount()
    {
        $data = $this->common();
        $data['tname'] = 'Lock user wallet';
        $data['userDetails'] = User::select('name','phone','id','username','wallet')->get();
        return view('admin.user.lockAmount',$data);
    }
    public function submitLockAmount(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'amount' => 'required|numeric|gt:0',
            'user' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERROR','message'=>$validator->errors()->first()]);
        }
        $amount = $res->amount;
        $user = $res->user;
        $userRow = User::find($user);
        if($userRow->wallet<$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient amount in user wallet']);
        $lamount = (double)$userRow->lamount + (int)$amount;
        try
        {
            $this->debitEntry($userRow->id,'LOCK-AMOUNT',"LOK".rand(1000000000,9999999999),$amount);
            User::where('id',$user)->update(['lamount'=>$lamount]);
            return response()->json(['status' => 'SUCCESS', 'message' => 'Amount locked successfully']);
        }catch(\Illuminate\Database\QueryException $e){
            return response()->json(['status' => 'ERROR', 'message' => 'Unknown error occured'], 500);
        }
    }
    public function releaseLockAmount()
    {
        $data = $this->common();
        $data['tname'] = 'Release Lock Amount';
        $data['userDetails'] = User::select('name','phone','id','username','lamount')->where('lamount','!=',0)->get();
        return view('admin.user.releaseLockAmount',$data);
    }
    public function submitReleaseLockAmount(Request $res)
    {
        $validator = Validator::make($res->all(), [
            'amount' => 'required|numeric|gt:0',
            'user' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERROR','message'=>$validator->errors()->first()]);
        }
        $amount = $res->amount;
        $user = $res->user;
        $userRow = User::find($user);
        if($userRow->lamount<$amount)
        return response()->json(['status'=>'ERROR','message'=>'Insufficient amount in user wallet']);
        $lamount = (double)$userRow->lamount - (int)$amount;
        try
        {
            $this->creditEntry($userRow->id,'RELESE-AMOUNT','REL-AMT'.rand(1000000000,9999999999),$amount);
            User::where('id',$user)->update(['lamount'=>$lamount]);
            return response()->json(['status' => 'SUCCESS', 'message' => 'Amount Released successfully']);
        }catch(\Illuminate\Database\QueryException $e){
            return response()->json(['status' => 'ERROR', 'message' => 'Unknown error occured'], 500);
        }
    }
    public function addEmployee()
    {
        $data = $this->common();
        $data['tname'] = 'Add new employee';
        return view('admin.employee.addEmployee',$data);
    }
    public function manageEmployee(Request $res)
    {
        $validator = Validator::make(json_decode($res->employeeData,true), [
            "name" => "required|string",
            "mobile" => "required|numeric|digits:10|unique:admin,phone",
            "email" => "required|email",
            "username" => "required|string|unique:admin,username",
            "password" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>'ERROR','message'=>$validator->errors()->first()]);
        }
        $employeeData = json_decode($res->employeeData);
        $name = $employeeData->name;
        $mobile = $employeeData->mobile;
        $email = $employeeData->email;
        $username = $employeeData->username;
        $password = $employeeData->password;
        $adminData = array(
            'name' => $name,
            'username' => $username,
            'password' => $password,
            'phone' => $mobile,
            'email' => $email,
            'profile' => 'https://flyjazz.ca/wp-content/uploads/2017/01/dummy-user-300x296.jpg'
            );
        $newRecord = AdminModel::create($adminData);
        $lastInsertedId = $newRecord->id;
        
        $addMember = $res->addMember?? "0";
        $listMember = $res->listMember?? "0";
        $userRequest = $res->userRequest?? "0";
        $upgradeMember = $res->upgradeMember?? "0";
        $userComplaints = $res->userComplaints?? "0";

        $pendingProfileKyc = $res->pendingProfileKyc?? "0";
        $profileKyc = $res->profileKyc?? "0";
        $aepsKyc = $res->aepsKyc?? "0";
            
        $accountApprovalRequest = $res->accountApprovalRequest?? "0";
        $pendingApprovalRequest = $res->pendingApprovalRequest?? "0";
            
        $wallet = $res->wallet?? "0";
        $creditFund = $res->creditFund?? "0";
        $debitFund = $res->debitFund?? "0";
        $pendingFundRequest = $res->pendingFundRequest?? "0";
        $fundRequest = $res->fundRequest?? "0";
        $lockAmount = $res->lockAmount?? "0";
        $releaseLockAmount = $res->releaseLockAmount?? "0";
            
        $walletTransactions = $res->walletTransactions?? "0";
        $aepsIcici = $res->aepsIcici?? "0";
        $bbps = $res->bbps?? "0";
        $recharge = $res->recharge?? "0";
        $dmt = $res->dmt?? "0";
        $payout = $res->payout?? "0";
        $quickTransfer = $res->quickTransfer?? "0";
        $panRegistration = $res->panRegistration?? "0";
        $panCoupon = $res->panCoupon?? "0";
            
        $pendingSupportTicket = $res->pendingSupportTicket?? "0";
        $supportTicket = $res->supportTicket?? "0";
            
        $mainSettings = $res->mainSettings?? "0";
        $package = $res->package?? "0";
        $commissionAndCharges = $res->commissionAndCharges?? "0";
        $regristrationFees = $res->regristrationFees?? "0";
        $userLoginLogs = $res->userLoginLogs?? "0";
        $adminLoginLogs = $res->adminLoginLogs?? "0";
            
        $addService = $res->addService?? "0";
        $listService = $res->listService?? "0";
            
        $companyBanks = $res->companyBanks?? "0";
        $manageServices = $res->manageServices?? "0";
            
        $addEmployee = $res->addEmployee?? "0";
        $viewEmployee = $res->viewEmployee?? "0";
        
        $insertData = array(
            'aid' => $lastInsertedId,
            "addMember" => $addMember,
            "listMember" => $listMember,
            "userRequest" => $userRequest,
            "upgradeMember" => $upgradeMember,
            "userComplaints" => $userComplaints,
            "pendingProfileKyc" => $pendingProfileKyc,
            "profileKyc" => $profileKyc,
            "aepsKyc" => $aepsKyc,
            "accountApprovalRequest" => $accountApprovalRequest,
            "pendingApprovalRequest" => $pendingApprovalRequest,
            "wallet" => $wallet,
            "creditFund" => $creditFund,
            "debitFund" => $debitFund,
            "pendingFundRequest" => $pendingFundRequest,
            "fundRequest" => $fundRequest,
            "lockAmount" => $lockAmount,
            "releaseLockAmount" => $releaseLockAmount,
            "walletTransactions" => $walletTransactions,
            "aepsIcici" => $aepsIcici,
            "bbps" => $bbps,
            "recharge" => $recharge,
            "dmt" => $dmt,
            "payout" => $payout,
            "quickTransfer" => $quickTransfer,
            "panRegistration" => $panRegistration,
            "panCoupon" => $panCoupon,
            "pendingSupportTicket" => $pendingSupportTicket,
            "supportTicket" => $supportTicket,
            "mainSettings" => $mainSettings,
            "package" => $package,
            "commissionAndCharges" => $commissionAndCharges,
            "regristrationFees" => $regristrationFees,
            "userLoginLogs" => $userLoginLogs,
            "adminLoginLogs" => $adminLoginLogs,
            "addService" => $addService,
            "listService" => $listService,
            "companyBanks" => $companyBanks,
            "manageServices" => $manageServices,
            "addEmployee" => $addEmployee,
            "viewEmployee" => $viewEmployee,
            );
        DB::table('adminValidation')->insert($insertData);
        return response()->json(['status'=>'SUCCESS','message'=>'Employee Created Successfully!!']);
    }
    public function unauthorize()
    {
        $logo = DB::table('settings')->where('name','logo')->first()->value;
        return view('errors.403',['logo'=>$logo]);
    }
    public function viewAllEmployee()
    {
        $data = $this->common();
        $data['tname'] = 'View all Employee';
        $data['allAdmin'] = AdminModel::join('adminValidation','admin.id','=','adminValidation.aid')->whereNotIn('admin.id',['1'])->select('admin.*','adminValidation.*')->get();
        return view('admin.employee.viewAllEmployee',$data);
    }
    public function adminRemoveEmployee($id)
    {
        $checkAdmin = AdminModel::find($id);
        if($checkAdmin == "" || $checkAdmin == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid request']);
        $checkAdminPrevilege = DB::table('adminValidation')->where('aid',$id)->first();
        if($checkAdminPrevilege == "" || $checkAdminPrevilege == null)
        return response()->json(['status'=>'ERROR','message'=>'Invalid request']);
        DB::table('admin')->where('id',$id)->delete();
        DB::table('adminValidation')->where('aid',$id)->delete();
        return response()->json(['status'=>'SUCCESS','message'=>'Employee remove successfully!!']);
    }
    public function updateEmployeePrevilege(Request $res)
    {
        $addMember = $res->addMember?? "0";
        $listMember = $res->listMember?? "0";
        $userRequest = $res->userRequest?? "0";
        $upgradeMember = $res->upgradeMember?? "0";
        $userComplaints = $res->userComplaints?? "0";

        $pendingProfileKyc = $res->pendingProfileKyc?? "0";
        $profileKyc = $res->profileKyc?? "0";
        $aepsKyc = $res->aepsKyc?? "0";
            
        $accountApprovalRequest = $res->accountApprovalRequest?? "0";
        $pendingApprovalRequest = $res->pendingApprovalRequest?? "0";
            
        $wallet = $res->wallet?? "0";
        $creditFund = $res->creditFund?? "0";
        $debitFund = $res->debitFund?? "0";
        $pendingFundRequest = $res->pendingFundRequest?? "0";
        $fundRequest = $res->fundRequest?? "0";
        $lockAmount = $res->lockAmount?? "0";
        $releaseLockAmount = $res->releaseLockAmount?? "0";
            
        $walletTransactions = $res->walletTransactions?? "0";
        $aepsIcici = $res->aepsIcici?? "0";
        $bbps = $res->bbps?? "0";
        $recharge = $res->recharge?? "0";
        $dmt = $res->dmt?? "0";
        $payout = $res->payout?? "0";
        $quickTransfer = $res->quickTransfer?? "0";
        $panRegistration = $res->panRegistration?? "0";
        $panCoupon = $res->panCoupon?? "0";
            
        $pendingSupportTicket = $res->pendingSupportTicket?? "0";
        $supportTicket = $res->supportTicket?? "0";
            
        $mainSettings = $res->mainSettings?? "0";
        $package = $res->package?? "0";
        $commissionAndCharges = $res->commissionAndCharges?? "0";
        $regristrationFees = $res->regristrationFees?? "0";
        $userLoginLogs = $res->userLoginLogs?? "0";
        $adminLoginLogs = $res->adminLoginLogs?? "0";
            
        $addService = $res->addService?? "0";
        $listService = $res->listService?? "0";
            
        $companyBanks = $res->companyBanks?? "0";
        $manageServices = $res->manageServices?? "0";
            
        $addEmployee = $res->addEmployee?? "0";
        $viewEmployee = $res->viewEmployee?? "0";
        
        $updateData = array(
            "addMember" => $addMember,
            "listMember" => $listMember,
            "userRequest" => $userRequest,
            "upgradeMember" => $upgradeMember,
            "userComplaints" => $userComplaints,
            "pendingProfileKyc" => $pendingProfileKyc,
            "profileKyc" => $profileKyc,
            "aepsKyc" => $aepsKyc,
            "accountApprovalRequest" => $accountApprovalRequest,
            "pendingApprovalRequest" => $pendingApprovalRequest,
            "wallet" => $wallet,
            "creditFund" => $creditFund,
            "debitFund" => $debitFund,
            "pendingFundRequest" => $pendingFundRequest,
            "fundRequest" => $fundRequest,
            "lockAmount" => $lockAmount,
            "releaseLockAmount" => $releaseLockAmount,
            "walletTransactions" => $walletTransactions,
            "aepsIcici" => $aepsIcici,
            "bbps" => $bbps,
            "recharge" => $recharge,
            "dmt" => $dmt,
            "payout" => $payout,
            "quickTransfer" => $quickTransfer,
            "panRegistration" => $panRegistration,
            "panCoupon" => $panCoupon,
            "pendingSupportTicket" => $pendingSupportTicket,
            "supportTicket" => $supportTicket,
            "mainSettings" => $mainSettings,
            "package" => $package,
            "commissionAndCharges" => $commissionAndCharges,
            "regristrationFees" => $regristrationFees,
            "userLoginLogs" => $userLoginLogs,
            "adminLoginLogs" => $adminLoginLogs,
            "addService" => $addService,
            "listService" => $listService,
            "companyBanks" => $companyBanks,
            "manageServices" => $manageServices,
            "addEmployee" => $addEmployee,
            "viewEmployee" => $viewEmployee,
            );
        DB::table('adminValidation')->where('aid',$res->adminId)->update($updateData);
        return response()->json(['status'=>'SUCCESS','message'=>'Employee Previlege Update Successfully!!']);
    }
    public function changeUserStatus($type,$uid)
    {
        $blockedUsers = ['deactivate', 'activate'];
         if (!in_array($type, $blockedUsers)) {
             abort(404);
        }
        if($type == 'deactivate')
        {
            $res = User::where('id',$uid)->update(['active'=>0]);
            if($res)
            return response()->json(['status'=>'SUCCESS','message'=>'User Deactivated successfully']);
            return response()->json(['status'=>'ERROR','message'=>'Unknown error occoured']);
        }
        else
        {
            $res = User::where('id',$uid)->update(['active'=>1]);
            if($res)
            return response()->json(['status'=>'SUCCESS','message'=>'User Activated successfully']);
            return response()->json(['status'=>'ERROR','message'=>'Unknown error occoured']);
        }
    }
    
    // public function checkstatus(Request $res)
    // {
    //      $txnid = $res->txnid;
    //      $getrow = DB::table('payouttxn')->where(['txnid' => $txnid, 'status' => 'PENDING']);
    //      $checkRow = $getrow->count();
    //      if($checkRow == 0){
    //          return response()->json(['status' => 'ERROR', 'message' => 'Txnid not found']);
    //      }
    //      $api_key = DB::table('settings')->where('name','api_key')->first()->value;
    //      $sendingdata = [
    //         "api_key" => $api_key,
    //         "txnid" => $txnid
    //         ];
    //     $sendingdata = json_encode($sendingdata);
    //     $url = "https://partner.ecuzen.in/new/api/payout/status";
    //     $ress = $this->callApi($url,'POST',$sendingdata,[]);
       
    //     $response = json_decode($ress);
        
    //     if($response->status == 'FAILED' && $response->msg == "Transaction not found")
    //     {
    //         $row = $getrow->first();
    //         $data = [
    //             'status' => $response->status,
    //             'txnid' => $txnid,
    //             'amount' => $row->amount,
    //             'rrn' => 'NA',
    //             'msg' => 'Transaction Failed',
    //             'mode' => $row->mode
    //             ];
    //             $res = json_encode($data);
    //             $url = url('/')."/api/payout-callback";
    //             $result = $this->callApi($url,'POST',$res,[]); 
    //             if($result == null)
    //             {
    //                 return response()->json(['status' => 'SUCCESS', 'message' => 'Transaction Failed']);
    //             }
    //     }elseif($response->status == 'SUCCESS'){
    //         $data = [
    //             'status' => $response->info->status,
    //             'txnid' => $txnid,
    //             'amount' => $row->info->amount,
    //             'rrn' => $response->info->rrn,
    //             'msg' => $response->info->message,
    //             'mode' => $row->mode
    //             ];
    //             $res = json_encode($data);
    //             $url = url('/')."/api/payout-callback";
    //             $result = $this->callApi($url,'POST',$res,[]); 
    //             if($result == null)
    //             {
    //                 return response()->json(['status' => 'SUCCESS', 'message' => $response->msg]);
    //             }
    //     }else{
    //          return response()->json(['status' => 'ERROR', 'message' => $response->msg]);
    //     }
        
    // }
    
     public function transactionReceiptDetails($type,$txnid)
    {
        //  return $type;
        $filter = [ 'aeps','bbps','recharge','express-payout','money-transfer','payout','qtransfer','uti','payment-gateway'];
        if(!in_array($type,$filter))
        return response()->json(['status'=>'ERROR','message'=>'Invalid Type']);
        $headerData = [];
        $bodyData = [];
        switch($type)
        {
            case 'aeps' :
                #check fortxnid 
                try {
                    $txnData = DB::table('iaepstxn')->where('txnid',$txnid)->first();
                    if($txnData == "" || $txnData == null)
                    return response()->json(['status'=>'ERROR','message'=>'Invalid transaction Id!!']);
                    $headerData['date'] = $txnData->date;
                    $headerData['type'] = $txnData->type;
                    $headerData['transaction_id'] = $txnData->txnid;
                    $headerData['txn_status'] = $txnData->status;
                    if($txnData->type == 'CW' || $txnData->type == 'AP')
                    $bodyData['transaction_amount'] = $txnData->txnamount;
                    $bodyData['remaining_amount'] = $txnData->amount;
                    $bodyData['message'] = $txnData->message;
                    $bodyData['aadhar'] = $txnData->aadhar;
                    $bodyData['bank'] = DB::table('banks')->where('id',$txnData->bank)->first()->name??'NA';
                    $bodyData['mobile'] = $txnData->mobile;
                    $bodyData['rrn'] = $txnData->rrn;
                    if($txnData->type == 'MSI')
                    $bodyData['mini_statement'] = json_decode($txnData->response)->mini_statement??[];
                }
                catch( \PDOException $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                catch( \Exception $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                break;
            case 'bbps' :
                try {
                    $txnData = DB::table('bbpstxn')->where('txnid',$txnid)->first();
                    if($txnData == "" || $txnData == null)
                    return response()->json(['status'=>'ERROR','message'=>'Invalid transaction Id!!']);
                    $headerData['date'] = $txnData->date;
                    $headerData['transaction_id'] = $txnData->txnid;
                    $headerData['txn_status'] = $txnData->status;
                    $headerData['amount'] = $txnData->amount;
                    $operator = DB::table('bbpsnewop')->where('id',$txnData->boperator)->first();
                    $bodyData[$operator->displayname] = $txnData->canumber;
                    $bodyData['operator'] = $operator->name;
                    
                }
                catch( \PDOException $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                catch( \Exception $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                break;
            case 'recharge' :
                try {
                    $txnData = DB::table('rechargetxn')->where('txnid',$txnid)->first();
                    if($txnData == "" || $txnData == null)
                    return response()->json(['status'=>'ERROR','message'=>'Invalid transaction Id!!']);
                    $headerData['date'] = $txnData->date;
                    $headerData['transaction_id'] = $txnData->txnid;
                    $headerData['txn_status'] = $txnData->status;
                    $headerData['amount'] = $txnData->amount;
                    $operator = DB::table('rechargeop')->where('id',$txnData->operator)->first();
                    $bodyData['operator'] = $operator->name;
                    $bodyData['mobile'] = $txnData->mobile;
                    $bodyData['message'] = $txnData->message;
                    
                }
                catch( \PDOException $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                catch( \Exception $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                break;
            case 'money-transfer' :
                try {
                    $txnData = DB::table('dmrtxn')->where('txnid',$txnid)->first();
                    if($txnData == "" || $txnData == null)
                    return response()->json(['status'=>'ERROR','message'=>'Invalid transaction Id!!']);
                    $headerData['date'] = $txnData->date;
                    $headerData['transaction_id'] = $txnData->txnid;
                    $headerData['mobile'] = $txnData->mobile;
                    $headerData['txn_status'] = $txnData->status;
                    $headerData['utr'] = $txnData->utr;
                    $headerData['amount'] = $txnData->amount;
                    $headerData['mode'] = $txnData->mode;
                    $bodyData['account'] = $txnData->account;
                    $bodyData['ifsc'] = $txnData->ifsc;
                    $bodyData['name'] = $txnData->bname;
                    $bodyData['message'] = $txnData->message;
                }
                catch( \PDOException $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                catch( \Exception $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                break;
                case 'express-payout' :
                try {
                    $txnData = DB::table('dmrtxn')->where('txnid',$txnid)->first();
                    if($txnData == "" || $txnData == null)
                    return response()->json(['status'=>'ERROR','message'=>'Invalid transaction Id!!']);
                    $headerData['date'] = $txnData->date;
                    $headerData['transaction_id'] = $txnData->txnid;
                    $headerData['txn_status'] = $txnData->status;
                    $headerData['mobile'] = $txnData->mobile;
                    $headerData['utr'] = $txnData->utr;
                    $headerData['amount'] = $txnData->amount;
                    $headerData['mode'] = $txnData->mode;
                    $bodyData['account'] = $txnData->account;
                    $bodyData['ifsc'] = $txnData->ifsc;
                    $bodyData['name'] = $txnData->bname;
                    $bodyData['message'] = $txnData->message;
                }
                catch( \PDOException $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                catch( \Exception $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                break;
            case 'payout' :
                try {
                    $txnData = DB::table('payouttxn')->where('txnid',$txnid)->first();
                    if($txnData == "" || $txnData == null)
                    return response()->json(['status'=>'ERROR','message'=>'Invalid transaction Id!!']);
                    $headerData['date'] = $txnData->date;
                    $headerData['transaction_id'] = $txnData->txnid;
                    $headerData['txn_status'] = $txnData->status;
                    $headerData['utr'] = $txnData->rrn;
                    $headerData['amount'] = $txnData->amount;
                    $headerData['mode'] = $txnData->mode;
                    $bodyData['account'] = $txnData->account;
                    $bodyData['ifsc'] = $txnData->ifsc;
                    $bodyData['name'] = $txnData->bname;
                    $bodyData['message'] = $txnData->message;
                }
                catch( \PDOException $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                catch( \Exception $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                break;
            case 'qtransfer' :
                try {
                    $txnData = DB::table('qtransfertxn')->where('txnid',$txnid)->first();
                    if($txnData == "" || $txnData == null)
                    return response()->json(['status'=>'ERROR','message'=>'Invalid transaction Id!!']);
                    $headerData['date'] = $txnData->date;
                    $headerData['transaction_id'] = $txnData->txnid;
                    $headerData['txn_status'] = $txnData->status;
                    $headerData['utr'] = $txnData->rrn;
                    $headerData['amount'] = $txnData->amount;
                    $headerData['mode'] = $txnData->mode;
                    $bodyData['account'] = $txnData->account;
                    $bodyData['ifsc'] = $txnData->ifsc;
                    $bodyData['name'] = $txnData->bname;
                    $bodyData['message'] = $txnData->message;
                }
                catch( \PDOException $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                catch( \Exception $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                break;
            case 'payment-gateway' :
                try {
                    $txnData = DB::table('pgTxn')->where('txnid',$txnid)->first();
                    if($txnData == "" || $txnData == null)
                    return response()->json(['status'=>'ERROR','message'=>'Invalid transaction Id!!']);
                    $headerData['date'] = $txnData->date;
                    $headerData['transaction_id'] = $txnData->txnid;
                    $headerData['txn_status'] = $txnData->status;
                    $headerData['amount'] = $txnData->amount;
                    $headerData['type'] = $txnData->type;
                    $bodyData['vpa'] = $txnData->vpa;
                    $bodyData['name'] = $txnData->name;
                    $bodyData['message'] = $txnData->message;
                }
                catch( \PDOException $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                catch( \Exception $e)
                { 
                    return response()->json(['status'=>'ERROR','message'=>$e->getMessage()]);
                }
                break;
        }
        $logo = DB::table('settings')->where('name','logo')->first()->value;
        $support = DB::table('settings')->where('name','cemail')->first()->value;
        $data = ['headerData'=>$headerData,'bodyData'=>$bodyData,'logo'=>$logo,'support'=>$support];
        return response()->json(['status'=>'SUCCESS','message'=>'Receipt Generated Successfully!!','view'=>view('receipt.allReceipt',$data)->render()]);
    }
    
     function itrform($service_id)
    {
        Paginator::useBootstrapFive();
        $service_entity = \App\Models\Vs_Service::where('id',$service_id)->first();
        $entities = \App\Models\ServiceForm::with('User:id,name,email,phone')->where('service_id',$service_id)->select('id','itr_user_name','firm_name','random_id','itr_email','itr_mobile','status','user_id')->paginate(10);
          $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
         $fav = DB::table('settings')->where('name', 'favicon')->first()->value;
         $dName = $wordsArray = explode(' ', $title);
        $dName = $wordsArray[0];
        $tname = 'Service';
          return view('admin.Service_Forms.index',compact('service_entity','service_id','entities','title','logo','fav','dName','tname'));
        // return $this->pview(view('partner.Service_Forms.index',compact('service_entity','service_id','entities')));
        
    }
}

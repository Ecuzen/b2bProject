<?php
namespace App\Http\Controllers;
use File;
use App\Models\ServiceForm;
use Illuminate\Http\Request;
use App\Traits\{Call_Api_Trait};
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth,Validator};
// use Illuminate\Support\Facades\DB;
use DB;
class ServiceForms extends Controller
{
    use Call_Api_Trait;
    function create(Request $req,$service_id)
    {
        $service_entity = \App\Models\Vs_Service::where('id',$service_id)->first();
        return view('ServiceForms.create',compact('service_entity','service_id'));
    }
    
    function index($service_id)
    {
        Paginator::useBootstrapFive();
        $authUserId = session('uid') ?? 0;
        $service_entity = \App\Models\Vs_Service::where('id',$service_id)->first();
        $entities = \App\Models\ServiceForm::with('User:id,name,email,phone')->where('service_id',$service_id)->where('user_id',$authUserId)->select('id','itr_user_name','firm_name','random_id','itr_email','itr_mobile','status','user_id')->paginate(10);;
        return view('ServiceForms.index',compact('service_entity','service_id','entities'));
    }
    
    function store(Request $request)
    {
        try
        {
            
            
             
            $uid = session('uid');
            $AuthUser = $user = $userData = DB::table('users')->where('id',$uid)->first();
            $userID = $AuthUser->id;
            //  return $userID;
            $package_id = $AuthUser->package;
            $service_id = $request->service_id;
            // echo '<pre>';
            // print_r($request->all());
            // die;
            switch ($service_id) 
            {   
                case '2':
                    $validator = Validator::make($request->all(), [
                        'gender' => 'required',
                        'pin_code' => 'required',
                        'service_id' => 'required|int',
                        'pan_card_dob' => 'required',
                        // 'form_16_file' => 'required',
                        'aadhar_number' => 'required|int|digits:12',
                        'pan_card_file' => 'required',
                        'pan_card_name' => 'required',
                        'itr_form_type' => 'required',
                        // 'bank_statement' => 'required',
                        'road_or_street' => 'required',
                        'pan_card_number' => 'required',
                        'residence_number' => 'required',
                        'locality_or_area' => 'required',
                        // 'miscellaneous_file' => 'required',
                        'pan_card_father_name' => 'required',
                        'back_side_aadhar_file' => 'required',
                        'front_side_aadhar_file' => 'required',
                        'city_or_town_or_district' => 'required',
                    ]);

                    if ($validator->fails())
                    {
                        // return back()->withErrors($validator)->withInput();
                        $error = $validator->errors()->first();
                       return response()->json(['error' => $error],401);
                    }

                    $url = "https://vyaparsamadhan.co.in/api/public-api/v1/service-forms/create";

                    $headers = [
                        "headerKey:".$this->Get_Vs_Api_Key(),
                        "ipAddress:".$request->ip(),
                    ];

                    $user_charges = \App\Models\User_Commission::where('service_id',$service_id)->where('package_id',$package_id)->first();
                    if(empty($user_charges))
                    {
                        return response()->json(['error' => 'Services Charge not set.Please Contact to admin.'],401);
                        // return back()->with('error',"Services Charge not set.Please Contact to admin.");
                    }

                    $rates = $user_charges->price ?? 400;
                    $commissions = $user_charges->commission ?? 160;

                    $formTotalPrice = $rates;
                    $walletBalance = $userData->wallet;

                    if ($walletBalance < $formTotalPrice)
                    {
                        // return back()->with('error',"User don't have sufficient balance!!!!");
                     return response()->json(['error' => 'User dont have sufficient balance!!!!'],401);

                    }


                    $response_data = $this->Vs_Call_Api_With_File( $url, $request->all(), $headers);
                    $response_data = json_decode($response_data);
                        // return $response_data;
                    if($response_data->status_code == 200)
                    {
                        $type = "Vs_services_Itr_Form";
                        $txnid = "VSSR".strtoupper(uniqid());
                        // $this->debitEntry(null,$user,$formTotalPrice,$txnid,$type);
                        $randomId = $response_data->random_id;
                           $this->debitEntry($userID,'ITR',$txnid,$formTotalPrice);
                        $frontSideAadarFile = $backSideAadarFile = $panCardFile = $form16File = $miscellaneouFile = $bankStatement = $schoolFeesFile = $licFile = $mutualFundFile = $sukanyaYohnaFile = NULL;
                        $insertArr = array();

                        if (!empty(request('front_side_aadhar_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('front_side_aadhar_file'));
                            $target_path = public_path('uploads/service_forms/'.request('front_side_aadhar_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['front_side_aadhar_file'] = request('front_side_aadhar_file');
                        }

                        if (!empty(request('back_side_aadhar_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('back_side_aadhar_file'));
                            $target_path = public_path('uploads/service_forms/'.request('back_side_aadhar_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['back_side_aadhar_file'] = request('back_side_aadhar_file');
                        }

                        if (!empty(request('pan_card_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('pan_card_file'));
                            $target_path = public_path('uploads/service_forms/'.request('pan_card_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['pan_card_file'] = request('pan_card_file');
                        }

                        if (!empty(request('form_16_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('form_16_file'));
                            $target_path = public_path('uploads/service_forms/'.request('form_16_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['form_16_file'] = request('form_16_file');
                        }

                        if (!empty(request('miscellaneous_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('miscellaneous_file'));
                            $target_path = public_path('uploads/service_forms/'.request('miscellaneous_file'));
                            @File::copy($base_path,$target_path);

                            $insertArr['miscellaneous_file'] = request('miscellaneous_file');
                        }

                        if (!empty(request('bank_statement')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('bank_statement'));
                            $target_path = public_path('uploads/service_forms/'.request('bank_statement'));
                            @File::copy($base_path,$target_path);

                            $insertArr['bank_statement'] = request('bank_statement');
                        }

                        if (!empty(request('lic_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('lic_file'));
                            $target_path = public_path('uploads/service_forms/'.request('lic_file'));
                            @File::copy($base_path,$target_path);

                            $insertArr['lic_file'] = request('lic_file');
                        }

                        if (!empty(request('mutual_fund_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('mutual_fund_file'));
                            $target_path = public_path('uploads/service_forms/'.request('mutual_fund_file'));
                            @File::copy($base_path,$target_path);

                            $insertArr['mutual_fund_file'] = request('mutual_fund_file');
                        }

                        if (!empty(request('sukanya_yohna_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('sukanya_yohna_file'));
                            $target_path = public_path('uploads/service_forms/'.request('sukanya_yohna_file'));
                            @File::copy($base_path,$target_path);

                            $insertArr['sukanya_yohna_file'] = request('sukanya_yohna_file');
                        }

                        if (!empty(request('housing_loan_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('housing_loan_file'));
                            $target_path = public_path('uploads/service_forms/'.request('housing_loan_file'));
                            @File::copy($base_path,$target_path);

                            $insertArr['housing_loan_file'] = request('housing_loan_file');
                        }

                        if (!empty(request('health_insurance_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('health_insurance_file'));
                            $target_path = public_path('uploads/service_forms/'.request('health_insurance_file'));
                            @File::copy($base_path,$target_path);

                            $insertArr['health_insurance_file'] = request('health_insurance_file');
                        }

                        if (!empty(request('school_fees_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('school_fees_file'));
                            $target_path = public_path('uploads/service_forms/'.request('school_fees_file'));
                            @File::copy($base_path,$target_path);

                            $insertArr['school_fees_file'] = request('school_fees_file');
                        }

                        if (!empty(request('firm_name')))
                        {
                            $insertArr['firm_name'] = request('firm_name');
                        }

                        if (!empty(request('declaration_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('declaration_file'));
                            $target_path = public_path('uploads/service_forms/'.request('declaration_file'));
                            @File::copy($base_path,$target_path);

                            $insertArr['declaration_file'] = request('declaration_file');
                        }

                        if (!empty(request('itr_user_name')))
                        {
                            $insertArr['itr_user_name'] = request('itr_user_name');
                        }

                        if (!empty(request('itr_password')))
                        {
                            $insertArr['itr_password'] = request('itr_password');
                        }

                        if (!empty(request('itr_mobile')))
                        {
                            $insertArr['itr_mobile'] = request('itr_mobile');
                        }

                        if (!empty(request('whatsapp_number')))
                        {
                            $insertArr['whatsapp_number'] = request('whatsapp_number');
                        }

                        if (!empty(request('itr_email')))
                        {
                            $insertArr['itr_email'] = request('itr_email');
                        }

                        if (!empty(request('gender')))
                        {
                            $insertArr['gender'] = request('gender');
                        }

                        if (!empty(request('service_id')))
                        {
                            $insertArr['service_id'] = request('service_id');
                        }

                        if (!empty(request('sf_amount')))
                        {
                            $insertArr['sf_amount'] = request('sf_amount');
                        }

                        if (!empty(request('lic_amount')))
                        {
                            $insertArr['lic_amount'] = request('lic_amount');
                        }

                        if (!empty(request('mf_amount')))
                        {
                            $insertArr['mf_amount'] = request('mf_amount');
                        }

                        if (!empty(request('sy_amount')))
                        {
                            $insertArr['sy_amount'] = request('sy_amount');
                        }

                        if (!empty(request('hl_amount')))
                        {
                            $insertArr['hl_amount'] = request('hl_amount');
                        }

                        if (!empty(request('hi_amount')))
                        {
                            $insertArr['hi_amount'] = request('hi_amount');
                        }

                        if (!empty(request('aadhar_number')))
                        {
                            $insertArr['aadhar_number'] = request('aadhar_number');
                        }

                        if (!empty(request('aadhar_address')))
                        {
                            $insertArr['aadhar_address'] = request('aadhar_address');
                        }

                        if (!empty(request('pan_card_number')))
                        {
                            $insertArr['pan_card_number'] = request('pan_card_number');
                        }

                        if (!empty(request('pan_card_name')))
                        {
                            $insertArr['pan_card_name'] = request('pan_card_name');
                        }

                        if (!empty(request('pan_card_father_name')))
                        {
                            $insertArr['pan_card_father_name'] = request('pan_card_father_name');
                        }

                        if (!empty(request('pan_card_dob')))
                        {
                            $insertArr['pan_card_dob'] = request('pan_card_dob');
                        }

                        if (!empty(request('pin_code')))
                        {
                            $insertArr['pin_code'] = request('pin_code');
                        }

                        if (!empty(request('residence_number')))
                        {
                            $insertArr['residence_number'] = request('residence_number');
                        }

                        if (!empty(request('road_or_street')))
                        {
                            $insertArr['road_or_street'] = request('road_or_street');
                        }

                        if (!empty(request('locality_or_area')))
                        {
                            $insertArr['locality_or_area'] = request('locality_or_area');
                        }

                        if (!empty(request('city_or_town_or_district')))
                        {
                            $insertArr['city_or_town_or_district'] = request('city_or_town_or_district');
                        }

                        if (!empty(request('itr_form_type')))
                        {
                            $insertArr['itr_form_type'] = request('itr_form_type');
                        }
                        $insertArr['user_id'] = $userID;
                        $insertArr['random_id'] = $randomId;
                    //   return $insertArr;
                        $create = ServiceForm::create($insertArr);
                        $service_form_id = $create->id;

                        // $bankData = json_decode(request('bank_data'));
                        $investData = json_decode(request('invest_data'));

                        $newWalletBalance = ($userData->wallet - $formTotalPrice);
                        $reserve_balance = ($userData->lamount + $formTotalPrice);

                        $AppRandomId = $response_data->application_id ?? '';
                        $random_Txn_Id = $response_data->txn_id ?? '';

                        // \App\Models\WalletLeadger::create([
                        //     "type" => "ITR FORM",
                        //     "amount" => $formTotalPrice,
                        //     "txn_id" => $random_Txn_Id,
                        //     "remarks" => "Your wallet has been debited the sum of $formTotalPrice",
                        //     "user_id" => $userID,
                        //     "txn_type" => "DEBIT",
                        //     "service_id" => request('service_id'),
                        //     "opening_balance" => $walletBalance,
                        //     "closing_balance" => $newWalletBalance,
                        // ]);

                        \App\Models\Transaction::create([
                            "txn_id" => $random_Txn_Id,
                            "amount" => $formTotalPrice,
                            "message" => 'Transaction is Pending!',
                            "status" => 'PENDING',
                            "user_id" => $userID,
                            "txn_type" => 'ITR',
                            "related_id" => $service_form_id,
                            "service_id" => request('service_id'),
                            "application_id" => $AppRandomId,
                        ]);

                        $bankDataArr = array();
                        $bank_name_data = request('bank_name');
                        if (!empty($bank_name_data))
                        {
                            $bincKey = 0;
                            foreach ($bank_name_data as $bank_key => $bank)
                            {
                                $bank_file_name = (!empty($request->bank_file_name[$bank_key])) ? $request->bank_file_name[$bank_key] : '';
                                $base_path = public_path('uploads/users/'.$userID.'/'.$bank_file_name);
                                $target_path = public_path('uploads/investments/'.$bank_file_name);

                                File::copy($base_path,$target_path);
                                $bankDataArr[$bincKey] = array(
                                    "user_id" => $userID,
                                    "bank_name" => $bank,
                                    "ifsc_code" => (!empty(request('ifsc_code')[$bank_key])) ? request('ifsc_code')[$bank_key] : '',
                                    "service_id" => request('service_id'),
                                    "account_type" => (!empty(request('account_type')[$bank_key])) ? request('account_type')[$bank_key] : '',
                                    "account_number" => (!empty(request('account_number')[$bank_key])) ? request('account_number')[$bank_key] : '',
                                    "service_form_id" => $service_form_id,
                                    "bank_statement_file" => $bank_file_name,
                                );
                                $bincKey++;
                            }
                        }

                        if (!empty($bankDataArr))
                        {
                            \App\Models\ServiceBankAccount::insert($bankDataArr);
                        }

                        $investFileArr = array();

                        $investData = $request->invest_name;
                        if (!empty($investData))
                        {
                            $incKey = 0;
                            foreach ($investData as $investKey => $invest)
                            {
                                $invest_files = (!empty($request->invest_file[$investKey])) ? $request->invest_file[$investKey] : '';
                                $invest_amounts = (!empty($request->invest_amount[$investKey])) ? $request->invest_amount[$investKey] : '';

                                $base_path = public_path('uploads/users/'.$userID.'/'.$invest_files);
                                $target_path = public_path('uploads/investments/'.$invest_files);

                                File::copy($base_path,$target_path);
                                $investFileArr[$incKey] = array(
                                    "user_id" => $userID,
                                    "service_id" => request('service_id'),
                                    "invest_file" => $invest_files,
                                    "invest_name" => $invest,
                                    "invest_amount" => $invest_amounts,
                                    "service_form_id" => $service_form_id,
                                );
                                $incKey++;
                            }
                        }
                        if (!empty($investFileArr))
                        {
                            \App\Models\Investment::insert($investFileArr);
                        }

                        if (!empty(request('fdr_intrest')) || !empty(request('sbi_income')) || !empty(request('rent_income')) || !empty(request('other_expenses')) || !empty(request('other_income_json')) || !empty(request('sale_deed_file_name')) || !empty(request('purchase_deed_file_name')) || !empty(request('sale_share_file_name')) )
                        {
                            $other_expense_arr = $income_other_arr = array();
                            if (!empty($request->other_expense_title))
                            {
                                $incO = 0;
                                foreach ($request->other_expense_title as $other_expense_key => $other_expense)
                                {
                                    $other_expense_arr[$incO] = array(
                                        "title" => $other_expense,
                                        "amount" => (!empty($request->other_expense_amount[$other_expense_key])) ? $request->other_expense_amount[$other_expense_key] : '',
                                    );
                                    $incO++;
                                }
                            }

                            if (!empty($request->income_title))
                            {
                                $incO = 0;
                                foreach ($request->income_title as $other_expense_key => $other_expense)
                                {
                                    $income_other_arr[$incO] = array(
                                        "title" => $other_expense,
                                        "amount" => (!empty($request->income_amount[$other_expense_key])) ? $request->income_amount[$other_expense_key] : '',
                                    );
                                    $incO++;
                                }
                            }

                            $incomeArr['user_id'] = $userID;
                            $incomeArr['service_id'] = request('service_id');
                            $incomeArr['sbi_income'] = request('sbi_income') ?? 0.00;
                            $incomeArr['fdr_intrest'] = request('fdr_intrest') ?? 0.00;
                            $incomeArr['rent_income'] = request('rent_income') ?? 0.00;
                            $incomeArr['is_sale_land'] = request('is_sale_land') ?? 0;
                            $incomeArr['is_sale_share'] = request('is_sale_share') ?? 0;
                            $incomeArr['other_expenses'] = json_encode($other_expense_arr);
                            $incomeArr['service_form_id'] = $service_form_id;
                            $incomeArr['other_income_json'] = json_encode($income_other_arr);

                            if (!empty(request('sale_deed_file_name')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('sale_deed_file_name'));
                                $target_path = public_path('uploads/service_forms/'.request('sale_deed_file_name'));
                                @File::copy($base_path,$target_path);

                                $incomeArr['sale_deed_file_name'] = request('sale_deed_file_name');
                            }

                            if (!empty(request('purchase_deed_file_name')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('purchase_deed_file_name'));
                                $target_path = public_path('uploads/service_forms/'.request('purchase_deed_file_name'));
                                @File::copy($base_path,$target_path);

                                $incomeArr['purchase_deed_file_name'] = request('purchase_deed_file_name');
                            }

                            if (!empty(request('sale_share_file_name')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('sale_share_file_name'));
                                $target_path = public_path('uploads/service_forms/'.request('sale_share_file_name'));
                                @File::copy($base_path,$target_path);

                                $incomeArr['sale_share_file_name'] = request('sale_share_file_name');
                            }
                            
                            // echo '<pre>';
                            // print_r($incomeArr);
                            // die;

                            \App\Models\Income::create($incomeArr);
                        }

                        if (!empty(request('business_firm_name')))
                        {
                            \App\Models\Business::create([
                                "cash" => request('cash') ?? 0.00,
                                "stock" => request('stock') ?? 0.00,
                                "user_id" => $userID,
                                "debtors" => request('debtors') ?? 0.00,
                                "creditors" => request('creditors') ?? 0.00,
                                "cash_sale" => request('cash_sale') ?? 0.00,
                                "bank_sale" => request('bank_sale') ?? 0.00,
                                "firm_name" => request('business_firm_name') ?? NULL,
                                "gst_number" => request('gst_number') ?? NULL,
                                "service_id" => request('service_id'),
                                "service_form_id" => $service_form_id,
                            ]);
                        }
                         return response()->json(['success' => $response_data->message],200);
 
                        // return to_route('user.index')->with('success',$response_data->message);
                    }
                    else
                    {
                        return response()->json(['error' =>$response_data->message],401);
                        // return to_route('service_form.create',$service_id)->with('error',$response_data->message);
                    }

                break;
                
                case '3':
                    $validator = Validator::make($request->all(), [
                        'firm_name' => 'required',
                        'itr_mobile' => 'required',
                        'service_id' => 'required|int',
                        'pan_card_file' => 'required',
                        'aadhar_number' => 'required',
                        'pan_card_number' => 'required',
                        'firm_description' => 'required',
                        'user_profile_file' => 'required',
                        'back_side_aadhar_file' => 'required',
                        'front_side_aadhar_file' => 'required',
                    ]);
                    
                    if ($validator->fails())
                    {
                         $error = $validator->errors()->first();
                       return response()->json(['error' => $error],401);
                        // return back()->withErrors($validator)->withInput();
                    }
                    
                    $url = "https://vyaparsamadhan.co.in/api/public-api/v1/service-forms/gst-registration";
                    
                    $headers = [
                        "headerKey:".$this->Get_Vs_Api_Key(),
                        "ipAddress:".$request->ip(),
                    ];
                    
                    $user_charges = \App\Models\User_Commission::where('service_id',$service_id)->where('package_id',$package_id)->first();
                    if(empty($user_charges))
                    {
                     return response()->json(['error' => 'Services Charge not set.Please Contact to admin.'],401);

                        // return back()->with('error',"Services Charge not set.Please Contact to admin.");
                    }
                    
                    $rates = $user_charges->price ?? 400;
                    $commissions = $user_charges->commission ?? 160;
                    
                    $formTotalPrice = $rates;
                    $walletBalance = $userData->limit();
        
                    if ($walletBalance < $formTotalPrice)
                    {
               return response()->json(['error' => 'User dont have sufficient balance!!!!'],401);

                        // return back()->with('error',"User don't have sufficient balance!!!!");
                    }
                    
                    $response_data = $this->Vs_Call_Api_With_File( $url, $request->all(), $headers);
                    $response_data = json_decode($response_data);
                    
                    if($response_data->status_code == 200)
                    {
                        $type = "Vs_services_Gst_Registration_Form";
                        $txnid = "VSSR".strtoupper(uniqid());
                        // $this->debitAmount(null,$user,$formTotalPrice,$txnid,$type);
                         $this->debitEntry($userID,'ITR',$txnid,$formTotalPrice);

                        $randomId = $response_data->random_id;
                        $insertArr = array();
                        $insertArr['user_id'] = $userID;
                        $insertArr['firm_name'] = request('firm_name');
                        $insertArr['random_id'] = $randomId;
                        $insertArr['service_id'] = request('service_id');
                        $insertArr['itr_mobile'] = request('itr_mobile');
                        $insertArr['aadhar_number'] = request('aadhar_number');
                        $insertArr['pan_card_number'] = request('pan_card_number');
                        $insertArr['firm_description'] = request('firm_description');
        
                        if (!empty(request('pan_card_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('pan_card_file'));
                            $target_path = public_path('uploads/service_forms/'.request('pan_card_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['pan_card_file'] = request('pan_card_file');
                        }
        
                        if (!empty(request('user_profile_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('user_profile_file'));
                            $target_path = public_path('uploads/service_forms/'.request('user_profile_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['user_profile_file'] = request('user_profile_file');
                        }
        
                        if (!empty(request('back_side_aadhar_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('back_side_aadhar_file'));
                            $target_path = public_path('uploads/service_forms/'.request('back_side_aadhar_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['back_side_aadhar_file'] = request('back_side_aadhar_file');
                        }
        
                        if (!empty(request('front_side_aadhar_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('front_side_aadhar_file'));
                            $target_path = public_path('uploads/service_forms/'.request('front_side_aadhar_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['front_side_aadhar_file'] = request('front_side_aadhar_file');
                        }
        
                        if (!empty(request('is_property_own')))
                        {
                            $is_property_own = request('is_property_own');
                            $insertArr['is_property_own'] = $is_property_own;
                            if (!empty(request('electricity_bill_file')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('electricity_bill_file'));
                                $target_path = public_path('uploads/service_forms/'.request('electricity_bill_file'));
                                @File::copy($base_path,$target_path);
                                $insertArr['electricity_bill_file'] = request('electricity_bill_file');
                            }
        
                            if (!empty(request('noc_certificate_file')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('noc_certificate_file'));
                                $target_path = public_path('uploads/service_forms/'.request('noc_certificate_file'));
                                @File::copy($base_path,$target_path);
                                $insertArr['noc_certificate_file'] = request('noc_certificate_file');
                            }
                        }
        
                        if (!empty(request('is_property_name_family_member')))
                        {
                            $is_property_name_family_member = request('is_property_name_family_member');
                            $insertArr['is_property_name_family_member'] = $is_property_name_family_member;
                            if (!empty(request('electricity_bill_file')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('electricity_bill_file'));
                                $target_path = public_path('uploads/service_forms/'.request('electricity_bill_file'));
                                @File::copy($base_path,$target_path);
                                $insertArr['electricity_bill_file'] = request('electricity_bill_file');
                            }
        
                            if (!empty(request('noc_certificate_file')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('noc_certificate_file'));
                                $target_path = public_path('uploads/service_forms/'.request('noc_certificate_file'));
                                @File::copy($base_path,$target_path);
                                $insertArr['noc_certificate_file'] = request('noc_certificate_file');
                            }
                        }
        
                        if (!empty(request('is_property_ranted')))
                        {
                            $is_property_ranted = request('is_property_ranted');
                            $insertArr['is_property_ranted'] = $is_property_ranted;
        
                            if (!empty(request('electricity_bill_file')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('electricity_bill_file'));
                                $target_path = public_path('uploads/service_forms/'.request('electricity_bill_file'));
                                @File::copy($base_path,$target_path);
                                $insertArr['electricity_bill_file'] = request('electricity_bill_file');
                            }
        
                            if (!empty(request('rent_agreement_stamp_file')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('rent_agreement_stamp_file'));
                                $target_path = public_path('uploads/service_forms/'.request('rent_agreement_stamp_file'));
                                @File::copy($base_path,$target_path);
                                $insertArr['rent_agreement_stamp_file'] = request('rent_agreement_stamp_file');
                            }
        
                            if (!empty(request('property_document_file')))
                            {
                                $base_path = public_path('uploads/users/'.$userID.'/'.request('property_document_file'));
                                $target_path = public_path('uploads/service_forms/'.request('property_document_file'));
                                @File::copy($base_path,$target_path);
                                $insertArr['property_document_file'] = request('property_document_file');
                            }
                        }
        
                        $create = ServiceForm::create($insertArr);
                        $service_form_id = $create->id;
        
                        $newWalletBalance = ($userData->wallet - $formTotalPrice);
                        $reserve_balance = ($userData->lamount + $formTotalPrice);
        
                        $AppRandomId = $response_data->application_id ?? '';
                        $random_Txn_Id = $response_data->txn_id ?? '';
        
                        // \App\Models\WalletLeadger::create([
                        //     "type" => "GST Verfication FORM",
                        //     "amount" => $formTotalPrice,
                        //     "txn_id" => $random_Txn_Id,
                        //     "remarks" => "Your wallet has been debited the sum of $formTotalPrice",
                        //     "user_id" => $userID,
                        //     "txn_type" => "DEBIT",
                        //     "service_id" => request('service_id'),
                        //     "opening_balance" => $walletBalance,
                        //     "closing_balance" => $newWalletBalance,
                        // ]);
        
                        \App\Models\Transaction::create([
                            "txn_id" => $random_Txn_Id,
                            "amount" => $formTotalPrice,
                            "message" => 'Transaction is Pending!',
                            "status" => 'PENDING',
                            "user_id" => $userID,
                            "txn_type" => 'GST',
                            "related_id" => $service_form_id,
                            "service_id" => request('service_id'),
                            "application_id" => $AppRandomId,
                        ]);
                        
                        $rrDir = public_path('uploads/users/'.$userID);
                        $this->rrmdir($rrDir); 
                        
                         return response()->json(['success' => $response_data->message],200);
                        // return to_route('user.index')->with('success',$response_data->message);
                    }
                    else
                    {
                        return response()->json(['error' =>$response_data->message],401);
                        // return to_route('service_form.create',$service_id)->with('error',$response_data->message);
                    }
                    
                break;
                case '4':
                case '5':
                    
                    $validator = Validator::make($request->all(), [
                        'itr_email' => 'required',
                        'firm_name' => 'required',
                        'service_id' => 'required|int',
                        'itr_mobile' => 'required',
                        'aadhar_number' => 'required|int|digits:12',
                        'pan_card_file' => 'required',
                        'employee_number' => 'required',
                        'pan_card_number' => 'required',
                        'alternate_phone' => 'required',
                        'gst_certificate' => 'required',
                        'user_profile_file' => 'required',
                        'gst_certificate_file' => 'required',
                        'back_side_aadhar_file' => 'required',
                        'front_side_aadhar_file' => 'required',
                    ]);
                    
                    if ($validator->fails())
                    {
                         $error = $validator->errors()->first();
                       return response()->json(['error' => $error],401);
                        // return back()->withErrors($validator)->withInput();
                    }
                    
                    if($service_id == 4)
                    {
                        $url = "https://vyaparsamadhan.co.in/api/public-api/v1/service-forms/pf-registration";
                    }
                    else if($service_id == 5)
                    {
                        $url = "https://vyaparsamadhan.co.in/api/public-api/v1/service-forms/esi-registration";
                    }
                    
                    $headers = [
                        "headerKey:".$this->Get_Vs_Api_Key(),
                        "ipAddress:".$request->ip(),
                    ];
                    
                    $user_charges = \App\Models\User_Commission::where('service_id',$service_id)->where('package_id',$package_id)->first();
                    if(empty($user_charges))
                    {
                         return response()->json(['error' => 'Services Charge not set.Please Contact to admin.'],401);
                        // return back()->with('error',"Services Charge not set.Please Contact to admin.");
                    }
                    
                    $rates = $user_charges->price ?? 400;
                    $commissions = $user_charges->commission ?? 160;
                    
                    $formTotalPrice = $rates;
                    $walletBalance = $userData->limit();
        
                    if ($walletBalance < $formTotalPrice)
                    {
                     return response()->json(['error' => 'User dont have sufficient balance!!!!'],401);

                        // return back()->with('error',"User don't have sufficient balance!!!!");
                    }
                    
                    $response_data = $this->Vs_Call_Api_With_File( $url, $request->all(), $headers);
                    $response_data = json_decode($response_data);
                    
                    if($response_data->status_code == 200)
                    {
                        if($service_id == 4)
                        {
                            $type = "Vs_services_Pf_Registration_Form";
                            $transaction_type = "PF Registration FORM";
                            $txn_type = "PF";
                        }
                        else if($service_id == 5)
                        {
                            $type = "Vs_services_Esic_Registration_Form";
                            $transaction_type = "ESI Registration FORM";
                            $txn_type = "ESI";
                            
                        }
                        $txnid = "VSSR".strtoupper(uniqid());
                        // $this->debitAmount(null,$user,$formTotalPrice,$txnid,$type);
                         $this->debitEntry($userID,'ITR',$txnid,$formTotalPrice);

                        $randomId = $response_data->random_id;
        
                        $insertArr['user_id'] = $userID;
                        $insertArr['itr_email'] = request('itr_email');
                        $insertArr['firm_name'] = request('firm_name');
                        $insertArr['random_id'] = $randomId;
                        $insertArr['service_id'] = request('service_id');
                        $insertArr['itr_mobile'] = request('itr_mobile');
                        $insertArr['aadhar_number'] = request('aadhar_number');
                        $insertArr['employee_number'] = request('employee_number');
                        $insertArr['pan_card_number'] = request('pan_card_number');
                        $insertArr['alternate_phone'] = request('alternate_phone');
                        $insertArr['gst_certificate'] = request('gst_certificate');
        
                        if (!empty(request('pan_card_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('pan_card_file'));
                            $target_path = public_path('uploads/service_forms/'.request('pan_card_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['pan_card_file'] = request('pan_card_file');
                        }
        
                        if (!empty(request('user_profile_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('user_profile_file'));
                            $target_path = public_path('uploads/service_forms/'.request('user_profile_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['user_profile_file'] = request('user_profile_file');
                        }
        
                        if (!empty(request('back_side_aadhar_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('back_side_aadhar_file'));
                            $target_path = public_path('uploads/service_forms/'.request('back_side_aadhar_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['back_side_aadhar_file'] = request('back_side_aadhar_file');
                        }
        
                        if (!empty(request('front_side_aadhar_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('front_side_aadhar_file'));
                            $target_path = public_path('uploads/service_forms/'.request('front_side_aadhar_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['front_side_aadhar_file'] = request('front_side_aadhar_file');
                        }
        
                        if (!empty(request('gst_certificate_file')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('gst_certificate_file'));
                            $target_path = public_path('uploads/service_forms/'.request('gst_certificate_file'));
                            @File::copy($base_path,$target_path);
                            $insertArr['gst_certificate_file'] = request('gst_certificate_file');
                        }
        
                        if (!empty(request('self_declaration_form_first')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('self_declaration_form_first'));
                            $target_path = public_path('uploads/service_forms/'.request('self_declaration_form_first'));
                            @File::copy($base_path,$target_path);
                            $insertArr['self_declaration_form_first'] = request('self_declaration_form_first');
                        }
        
                        if (!empty(request('self_declaration_form_second')))
                        {
                            $base_path = public_path('uploads/users/'.$userID.'/'.request('self_declaration_form_second'));
                            $target_path = public_path('uploads/service_forms/'.request('self_declaration_form_second'));
                            @File::copy($base_path,$target_path);
                            $insertArr['self_declaration_form_second'] = request('self_declaration_form_second');
                        }
        
                        $create = ServiceForm::create($insertArr);
                        if ($create)
                        {
                            $service_form_id = $create->id;
        
                            $reserve_balance = ($userData->lamount + $formTotalPrice);
                            $newWalletBalance = ($userData->wallet - $formTotalPrice);
        
                            $AppRandomId = $response_data->application_id ?? '';
                            $random_Txn_Id = $response_data->txn_id ?? '';
        
                            // \App\Models\WalletLeadger::create([
                            //     "type" => $transaction_type,
                            //     "amount" => $formTotalPrice,
                            //     "txn_id" => $random_Txn_Id,
                            //     "remarks" => "Your wallet has been debited the sum of $formTotalPrice",
                            //     "user_id" => $userID,
                            //     "txn_type" => "DEBIT",
                            //     "service_id" => request('service_id'),
                            //     "opening_balance" => $walletBalance,
                            //     "closing_balance" => $newWalletBalance,
                            // ]);
        
                            \App\Models\Transaction::create([
                                "txn_id" => $random_Txn_Id,
                                "amount" => $formTotalPrice,
                                "message" => 'Transaction is Pending!',
                                "status" => 'PENDING',
                                "user_id" => $userID,
                                "txn_type" => $txn_type,
                                "related_id" => $service_form_id,
                                "service_id" => request('service_id'),
                                "application_id" => $AppRandomId,
                            ]);
                            
                            $rrDir = public_path('uploads/users/'.$userID);
                            $this->rrmdir($rrDir);
                        }
                        return response()->json(['success' => $response_data->message],200);
                        // return to_route('user.index')->with('success',$response_data->message);
                    }
                    else
                    {
                         return response()->json(['error' =>$response_data->message],401);

                        // return to_route('service_form.create',$service_id)->with('error',$response_data->message);
                    }
                break;
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
}

<?php
use App\Http\Controllers\Psa;
use App\Http\Controllers\Dmt;
use App\Http\Controllers\Cron;
use App\Http\Controllers\Test;
use App\Http\Controllers\Bbps;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Payout;
use App\Http\Controllers\Website;
use App\Http\Controllers\Recharge;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Service_Form;
use App\Http\Controllers\Transactions;
use App\Http\Controllers\Notifications;
use App\Http\Controllers\AepsController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BbpsController;
use App\Http\Controllers\PaymentGateway;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\TestTwoController;
use App\Http\Controllers\QtransferController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ServiceForms;
use App\Http\Controllers\Settings;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*Website*/
//Route::get('/', [Website::class, 'index'])->name('website');


/*MainController*/
Route::get('/', function(){
    return redirect()->route('user.login');
})->name('website');

Route::get('/testmail',[MainController::class,'sendEmail']);
Route::get('/login', [MainController::class, 'userLogin'])->name('user.login');
Route::post('/login',[MainController::class,'login']);
Route::post('/loginverify',[MainController::class,'loginverify']);

Route::get('/user-logout',[MainController::class,'logout'])->name('logout');

Route::get('/new-user-register',[MainController::class,'signup'])->name('newUserRegister');
Route::post('/user-register',[MainController::class,'registerUser'])->name('registerUser');

Route::get('/reset/{type}/{token}',[ResetPasswordController::class,'index'])->name('reset');
Route::get('/forgot-pass/{type}', [MainController::class, 'forgotpass']);
Route::post('/forgot-pass-mail',[MainController::class,'forgotsend']);

Route::get('/pendingk', [MainController::class, 'kycPending'])->name('pendingk');

Route::middleware(['checkService'])->group(function () {
Route::get('/dashboard', [MainController::class, 'index'])->name('home');
Route::get('/logout', [MainController::class, 'logout']);
Route::get('/kyc', [MainController::class, 'kyc']);
Route::get('/profile', [MainController::class, 'profile']);
Route::get('/addmember',[MainController::class,'addmember']);
Route::post('/add-member',[MainController::class,'memberreg']);
Route::get('/addfund',[MainController::class,'addfund']);
Route::post('/addfund-submit',[MainController::class,'addfundsubmit']);
Route::get('/user-profile-settings',[MainController::class,'userProfileSettings'])->name('userProfileSettings');
Route::post('/user-update/{type}',[MainController::class,'updateProfileSettings'])->name('updateProfileSettings');

//payout 
Route::get('/payout', [PayoutController::class, 'index'])->name('payoutIndex');
Route::POST('/payout-add-account',[PayoutController::class,'addaccount']);
Route::POST('/payout-delete-account',[PayoutController::class,'delaccount']);
Route::POST('/initiate-payout',[PayoutController::class,'initiate']);
Route::post('/payout-transaction',[PayoutController::class,'transaction']);


Route::controller(Service_Form::class)->prefix('service-form')->group(function ()
{
    Route::get('/{service_id?}', 'index')->name('service_form.index');
});


//AepsController
Route::get('/aeps', [AepsController::class, 'index']);
Route::post('/submit-aeps-kyc', [AepsController::class, 'dokyc']);
Route::post('/aeps-send-otp', [AepsController::class, 'kycSendotp']);
Route::post('/aeps-verify-otp', [AepsController::class, 'verifyotp']);
Route::post('/aeps-verify-finger', [AepsController::class, 'verifyfinger']);
Route::post('/aeps-balance-enquiry', [AepsController::class, 'balanceenq']);
Route::post('/aeps-mini-statement', [AepsController::class, 'miniStatement']);
Route::post('/aeps-cash-withdrawal', [AepsController::class, 'cashWithdrawal']);
Route::post('/aeps-cash-aadharpay', [AepsController::class, 'aadharPay']);

Route::post('/aeps-verify-tfa-finger', [AepsController::class, 'tfaVerification'])->name('tfaVerification');
#aadharpay tfa
Route::get('/do-ap-tfa',[AepsController::class,'loadApTfa'])->name('loadApTfa');


Route::post('/aeps-verify-aptfa-finger', [AepsController::class, 'apTfaVerification'])->name('apTfaVerification');


#dmt activity
Route::get('/money-transfer',[Dmt::class,'index'])->name('dmt');
Route::post('/dmt-login',[Dmt::class,'login'])->name('dmtLogin');
Route::post('/dmt-register/send-data',[Dmt::class,'register'])->name('dmtRegister');
Route::middleware(['dmt'])->group(function () {
Route::get('/dmt-call-account-data',[Dmt::class,'dmtData'])->name('dmtAccountData');
Route::any('dmt-logout',[Dmt::class,'dmtLogout'])->name('dmtLogout');
Route::post('/dmt-addaccount',[Dmt::class,'addaccount'])->name('dmtAddAccount');
Route::get('dmt-delete-account/{account}',[Dmt::class,'deleteAccount'])->name('dmtDeleteAccount');
Route::post('/initiate-dmt-transaction',[Dmt::class,'initiateTransaction'])->name('dmtInitiateTransaction');
Route::post('/do-dmt-transactions',[Dmt::class,'doTransaction'])->name('doTransaction');
Route::post('/dmt-account-verify',[Dmt::class,'dmtAccountVerify'])->name('dmtAccountVerify');
});


#qtransfer activity
Route::get('/quick-transfer', [QtransferController::class, 'index']);
Route::get('/fetch-data-by-number', [QtransferController::class, 'fetch']);
Route::post('/qtransfer-initiate',[QtransferController::class,'initiate']);
Route::post('qtransfer-transaction',[QtransferController::class,'transaction']);


#Bbps activity
Route::get('/bbps-index',[Bbps::class,'index']);
Route::get('/get-operators-by-cat',[Bbps::class,'opbyCat']);
Route::get('get-biller-params',[Bbps::class,'getParams']);
Route::post('/bbps-fetch-bill',[Bbps::class,'fetchBill']);
Route::post('/bbps-pay-bill',[Bbps::class,'payBill']);

#mail activity


#Recharge
Route::get('/recharge-mobile',[Recharge::class,'mobile']);
Route::any('/recharge-fetch-operator',[Recharge::class,'fetchoperator']);

#PSA
Route::get('/psa',[Psa::class,'index']);
Route::POST('/submit-psa',[Psa::class,'submitPsa']);
Route::POST('/buy-uti',[Psa::class,'buy']);

#basic 
Route::get('/commission-charges',[MainController::class,'commCharge'])->name('commCharge');
Route::get('/user-fetch-package-commission/{item}/{package}',[MainController::class,'fetchCommCharge'])->name('fetchCommCharge');
Route::get('/user-raise-ticket',[MainController::class,'raiseTicket'])->name('raiseTicket');
Route::post('/user-submit-ticket',[MainController::class,'submitComplain'])->name('submitComplain');



#transactions
Route::get('/transactions/{type}',[Transactions::class,'index']);
Route::get('/fetch-transaction/{type}',[Transactions::class,'fetch']);

#wallet to wallet
Route::get('/wallet-to-wallet',[MainController::class,'walletTowallet'])->name('walletTowallet');
Route::get('/fetch-user-by-mobile/{mobile}',[MainController::class,'fetchUser'])->name('fetchUser');
Route::post('/wallet-to-wallet-credit',[MainController::class,'walletToWalletCredit'])->name('walletToWalletCredit');

#payment gateway
Route::get('/add-fund-by-pg',[PaymentGateway::class,'index'])->name('addfundByPg');
Route::post('/add-fund-by-pg-initiate',[PaymentGateway::class,'initiateTransaction'])->name('addFundByPg');

#Account verify
Route::post('/account-verify',[MainController::class,'accountVerify'])->name('accountVerify');
Route::get('/other-services',[MainController::class,'otherServices'])->name('otherServices');


#new bbps 
    Route::controller(BbpsController::class)->prefix('bbps')->group(function(){
        Route::get('/','index')->name('bbps.index');
        Route::get('/fetch/{cat_key}','fetch_by_cat')->name('bbps.fetch_by_cat');
        Route::get('/fetch-params/{billerId}','get_params')->name('bbps.fetch_biller.params');
        Route::post('/fetch_bill','fetch_bill')->name('bbps.fetch_bill');
        Route::post('/pay_bill','pay_bill')->name('bbps_new.paybill');
    });
    
    Route::controller(BbpsController::class)->prefix('subscription')->group(function (){
        Route::get('/{type}','subscription')->name('subscription.index');
        Route::get('/{type}/{id}/{name}','get_details')->name('subscription.get_details');
        Route::post('/fetch_bill','subscription_fetch_bill')->name('subscription.fetch_bill');
        Route::post('/pay_bill','subscription_pay_bill')->name('subscription.paybill');
    });
    
    Route::prefix('other-service')->controller(MainController::class)->group(function (){
        Route::get('pan_verification',function (){
            return view('otherServices.panVerification');
        })->name('user.pan_verification');
        Route::post('pan_verification','pan_verification_details')->name('verifyPan');
    });
    
       Route::post('web-file-uploads',[Settings::class,'Web_File_Uploads'])->name('web.file_uploads');

 Route::controller(ServiceForms::class)->prefix('service-form')->group(function()
    {
        Route::get('create/{id}','create')->name('service_form.create') ;
        Route::post('store','store')->name('service_form.store') ;
        Route::get('index/{id}','index')->name('service_form.index') ;
        Route::post('ajax-service-form','Ajax_Service_Form')->name('service_form.ajax_service_form') ;
    });

});
Route::post('/submit-kyc',[MainController::class,'submitkyc']);
Route::get('/kyc-form',[MainController::class,'kyc_form'])->name('kyc-form');

Route::prefix('aadhar_verification')->controller(MainController::class)->group(function (){
   Route::post('send_otp','aadhar_send_otp')->name("aadhar_verification_send_otp"); 
   Route::post('validate_otp','aadhar_validate_otp')->name("aadhar_verification_validate_otp"); 
});

#testingByAachuki
Route::get('/test',[Test::class,'index'])->name('test');
// https://partner.ecuzen.in/new/api/recharge/dorecharge
Route::post('/do-recharge',[Recharge::class,'doRecharge']);



#admin section
Route::get('/admin',[Admin::class,'index'])->name('admin');
Route::post('/admin-login',[Admin::class,'login'])->name('adminLogin');
Route::post('/admin-login-verify',[Admin::class,'loginVverify'])->name('loginVverify');

Route::middleware(['admin'])->group(function () {
    
Route::get('/fetch-list-member/{type}',[Admin::class,'listMember'])->name('listMember');
Route::get('/admin-member-login/{memberId}',[Admin::class,'userLogin'])->name('userLogin');
Route::get('/admin-member-service/{memberId}',[Admin::class,'userService'])->name('userService');
Route::get('/admin-member-view/{memberId}',[Admin::class,'userView'])->name('userView');
Route::get('/admin-logout',[Admin::class,'adminLogout'])->name('adminLogout');
Route::get('/admin-add-user',[Admin::class,'adminAdduser'])->name('adminAdduser');
Route::post('/add-user',[Admin::class,'addUser'])->name('addUser');
Route::get('/user-request',[Admin::class,'userRequest'])->name('userRequest');
Route::post('/approve-user-request',[Admin::class,'approveUser'])->name('approveUser');
Route::get('/reject-user/{id}',[Admin::class,'rejectUser'])->name('rejectUser');
Route::get('/login-logs/{type}',[Admin::class,'loginLogs'])->name('loginLogs');
Route::get('/user-tickets/{type}',[Admin::class,'userTickets'])->name('userTickets');
Route::post('/close-user-ticket',[Admin::class,'closeTicket'])->name('closeTicket');
Route::get('/user-pending-kyc/{type}',[Admin::class,'pendingKyc'])->name('pendingKyc');
Route::get('/approve-kyc/{id}',[Admin::class,'approveKyc'])->name('approveKyc');
Route::get('/reject-kyc/{id}',[Admin::class,'rejectKyc'])->name('rejectKyc');
Route::get('/profile-kyc-delete/{id}',[Admin::class,'rejectKyc'])->name('rejectKyc');
Route::get('/users-aeps-kyc',[Admin::class,'aepsKyc'])->name('aepsKyc');

Route::get('/aeps-kyc-delete/{id}',[Admin::class,'aepsKycdelete'])->name('aepsKycdelete');
Route::get('/payout-account-request/{type}',[Admin::class,'accountApproval'])->name('accountApproval');
Route::get('/approve-account/{id}',[Admin::class,'approveAccount'])->name('approveAccount');
Route::get('/reject-payout-account/{id}',[Admin::class,'rejectAccount'])->name('rejectAccount');
Route::get('/payout-account-delete/{id}',[Admin::class,'rejectAccount'])->name('deleteAccount');
Route::get('/wallet-ledger',[Admin::class,'walletHistory'])->name('walletHistory');
Route::get('/user-fund/{type}',[Admin::class,'userWallet'])->name('userWallet');
Route::post('/wallet-fund/{type}',[Admin::class,'walletActivity'])->name('walletActivity');
Route::get('/topup-requests/{type}',[Admin::class,'walletTopup'])->name('walletTopup');
Route::get('/topup-request-action/{type}/{id}',[Admin::class,'topupAction'])->name('topupAction');
Route::get('/manage-package',[Admin::class,'managePackage'])->name('managePackage');
Route::any('/admin-package-action/{type}/{id}',[Admin::class,'packageAction'])->name('packageAction');
Route::post('/admin-create-package',[Admin::class,'createPackage'])->name('createPackage');
Route::get('/admin-package-commission',[Admin::class,'packageCommission'])->name('packageCommission');
Route::get('/admin-fetch-package-commission/{item}/{package}',[Admin::class,'fetchCommCharge'])->name('fetchCommCharge');
Route::post('/admin-save-comm-charges',[Admin::class,'saveCommCharge'])->name('saveCommCharge');
Route::get('/manage-settings',[Admin::class,'manageSettings'])->name('manageSettings');
Route::post('/submit-manage-setting',[Admin::class,'submitSetting'])->name('submitSetting');
Route::get('/admin-add-company-account',[Admin::class,'addCompanyAccount'])->name('addCompanyAccount');
Route::post('/add-bank-account',[Admin::class,'addBankAccount'])->name('addBankAccount');
Route::get('/admin-manage-services',[Admin::class,'manageService'])->name('manageService');
Route::post('/admin-save-changes-services',[Admin::class,'saveServiceChanges'])->name('saveServiceChanges');
Route::any('/admin-manage-bank-account/{type}/{id}',[Admin::class,'adminDeleteBank'])->name('adminDeleteBank');
Route::get('/admin-other-services/{type}',[Admin::class,'adminOtherServices'])->name('adminOtherServices');
Route::post('/admin-add-other-service',[Admin::class,'adminAddOtherservice'])->name('adminAddOtherservice');
Route::get('/admin-delete-other-service/{id}',[Admin::class,'adminDeleteOtherservice'])->name('adminDeleteOtherservice');
Route::get('/admin-profile-settings',[Admin::class,'profileSettings'])->name('profileSettings');
Route::post('/admin-reset-password',[Admin::class,'resetPassword'])->name('resetPassword');
Route::post('/admin-update-users-services',[Admin::class,'updateUserService'])->name('updateUserService');
Route::get('/admin-member-edit/{id}',[Admin::class,'adminUserEdit'])->name('adminUserEdit');
Route::post('/admin-update-user-details',[Admin::class,'adminUpdateUserDetails'])->name('adminUpdateUserDetails');
Route::get('/upgrade-member',[Admin::class,'upgradeMember'])->name('upgradeMember');
Route::post('/admin-submit-upgrade-member/{type}',[Admin::class,'submitUpgradeMember'])->name('submitUpgradeMember');
Route::get('/lock-user-amount',[Admin::class,'lockAmount'])->name('lockAmount');
Route::post('/submit-lock-amount',[Admin::class,'submitLockAmount'])->name('submitLockAmount');
Route::get('/release-lock-amount',[Admin::class,'releaseLockAmount'])->name('releaseLockAmount');
Route::post('/submit-release-lock-amount',[Admin::class,'submitReleaseLockAmount'])->name('submitReleaseLockAmount');
Route::get('/admin-add-employee',[Admin::class,'addEmployee'])->name('addEmployee');
Route::post('/add-employee',[Admin::class,'manageEmployee'])->name('manageEmployee');
Route::get('/admin-manage-all-employee',[Admin::class,'viewAllEmployee'])->name('viewAllEmployee');
Route::get('/unauthorize',[Admin::class,'unauthorize'])->name('unauthorize');
Route::get('admin-remove-employee/{id}',[Admin::class,'adminRemoveEmployee'])->name('adminRemoveEmployee');
Route::post('admin-update-employee-previlege',[Admin::class,'updateEmployeePrevilege'])->name('updateEmployeePrevilege');
Route::get('/change-user-status/{type}/{id}',[Admin::class,'changeUserStatus'])->name('changeUserStatus');


Route::get('/manage-commission',[Admin::class,'commission'])->name('commission');

#transactions 
Route::get('/admin-fetch-transactions/{type}',[Admin::class,'adminFetchtransaction'])->name('adminFetchtransaction');
Route::get('/admin-get-transaction/{type}',[Admin::class,'adminGettransaction'])->name('adminGettransaction');
Route::post('/check_status',[Admin::class,'checkstatus'])->name('checkstatus');

Route::get('/transactions-receipt-details/{type}/{txnid}',[Admin::class,'transactionReceiptDetails'])->name('transactionReceiptDetails');

      Route::get('service/index/{id}',[Admin::class,'itrform'])->name('pservice_form.index');
    //   Route::post('ajax-service-form',[Admin::class,'Ajax_Service_Form'])->name('pservice_form.ajax_service_form');


Route::get('notifications',[Notifications::class,'index'])->name('notification.index');
Route::post('notifications-store',[Notifications::class,'store'])->name('notification.store');
Route::get('cron-notify',[Cron::class,'cronNotify'])->name('cron.cron_notify');
});


// 
Route::get('/user-commission',[TestController::class,'index']);
Route::get('/commission-test',[TestTwoController::class,'dataGet']);


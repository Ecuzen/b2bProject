<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Android;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Callback;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BbpsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

#callback urls 
Route::any('/dmt-callback', [Callback::class, 'dmt'])->name('dmtCallBack');
Route::any('/dmt-account-verify-callback', [Callback::class, 'dmtAccountVerify'])->name('dmtAccountVerifyCallBack');
Route::any('/quick-transfer-callback', [Callback::class, 'quickTransferCallBack'])->name('quickTransferCallBack');
Route::any('/payout-callback', [Callback::class, 'payoutCallback'])->name('payoutCallback');
Route::any('/recharge-callback', [Callback::class, 'recharge'])->name('recharge');
Route::any('/payment-gateway-callback', [Callback::class, 'ecuzenPgCallback'])->name('paymentGatewayCallback');
Route::any('/pg', [Callback::class, 'ecuzenPg']);


#Basic apis
#test notification
Route::get('/test-notification', [Android::class, 'testNotification']);
Route::post('/login', [Android::class, 'login']);
Route::get('/getRole', [Android::class, 'getRole']);
Route::post('/register', [Android::class, 'register']);
Route::get('/getrdname', [Android::class, 'rdName']);
Route::get('/getbank', [Android::class, 'getBankList']);
Route::post('/loginVerify', [Android::class, 'loginVerify'])->name('loginVerify');
Route::post('/forgot/{type}', [Android::class, 'userForgot'])->name('userForgot');
Route::get('/getAllstate', [Android::class, 'getAllstate'])->name('getAllstate');

Route::middleware(['android'])->group(function () {
    Route::post('submit-kyc',[android::class,'submitKyc'])->name('submitKyc');
    Route::get('view-profile',[android::class,'view_profile'])->name('user.view_profile');
    Route::get('wallet',[android::class,'wallet'])->name('user.wallet');
    Route::post('topup',[android::class,'topup'])->name('user.topup');
    Route::get('addfund-banklist',[android::class,'addfundBankList'])->name('addfundBankList');
    Route::post('update-user-location',[android::class,'update_user_location'])->name('user.update_user_location');
    Route::post('update-password',[android::class,'update_password'])->name('user.update_password');
    Route::post('update-pin',[android::class,'update_pin'])->name('user.update_pin');
    Route::post('update-profile',[android::class,'update_profile'])->name('user.update_profile');
    Route::get('contact-data',[android::class,'contact_data'])->name('user.contact_data');
    Route::get('services',[android::class,'services'])->name('user.services');
   
    
    
    Route::prefix('bbps')->group(function(){
        Route::post('billers',[Android::class,'bbps_billers']);
        Route::post('fetch_bill',[Android::class,'bbps_fetch_bill']);
        Route::post('pay_bill',[Android::class,'bbps_pay_bill']);
        Route::get('all_categories',[Android::class,'bbpsAllBillers'])->name('bbpsAllBillers');
    });
    
    #AEPS
    Route::prefix('aeps')->group(function(){
        Route::get('checkKyc',[Android::class,'checkKyc'])->name('checkKyc');
        Route::post('dokyc',[Android::class,'aepskyc'])->name('Aepskyc');
        Route::get('send-otp',[Android::class,'aepsSendotp'])->name('aepsSendotp');
        Route::post('verify-otp',[Android::class,'aepsVerifyOtp'])->name('aepsVerifyOtp');
        Route::post('verify-finger',[Android::class,'verifyFinger'])->name('verifyFinger');
        Route::post('tfa-verification',[Android::class,'tfaVerification'])->name('tfaVerification');
        Route::post('ap-tfa-verification',[Android::class,'apTfaVerification'])->name('apTfaVerification');
        Route::post('balance-enquiry',[Android::class,'balanceEnquiry'])->name('balanceEnquiry');
        Route::post('mini-statement',[Android::class,'miniStatement'])->name('miniStatement');
        Route::post('cash-withdrawal',[Android::class,'cashWithdrawal'])->name('cashWithdrawal');
        Route::post('aadhar-pay',[Android::class,'aadharPay'])->name('aadharPay');
    });
    # Recharge
    Route::prefix('recharge')->group(function(){
        Route::get('get-operator/{type}',[Android::class,'getOperatorRecharge'])->name('getOperatorRecharge');
        Route::post('fetch-operator',[Android::class,'fetchOperator'])->name('fetchOperator');
        Route::post('fetch-plans',[Android::class,'fetchPlans'])->name('fetchPlans');
        Route::post('do-recharge',[Android::class,'doRecharge'])->name('doRecharge');
    });
     # Quick Transfer
    Route::prefix('qtransfer')->group(function(){
        Route::post('fetch-account',[Android::class,'fetchQtAccount'])->name('fetchQtAccount');
        Route::post('initiate-transaction',[Android::class,'initiateQtTransaction'])->name('initiateQtTransaction');
        Route::post('qt-do-transaction',[Android::class,'qtDoTxn'])->name('qtDoTxn');
        Route::get('getAllBankIfsc', [Android::class, 'getAllBankIfsc'])->name('getAllBankIfsc');
         Route::post('accountVerify',[android::class,'accountVerify'])->name('accountVerify');
    });
    # Payout
    Route::prefix('payout')->group(function(){
        Route::get('get-accounts',[Android::class,'getPayoutAccounts'])->name('getPayoutAccounts');
        Route::post('add-account',[Android::class,'addPayoutAccount'])->name('addPayoutAccount');
        Route::post('delete-account',[Android::class,'deletePayoutAccount'])->name('deletePayoutAccount');
        Route::post('initiate-transaction',[Android::class,'payoutInitiateTransaction'])->name('payoutInitiateTransaction');
        Route::post('do-transaction',[Android::class,'payoutDoTransaction'])->name('payoutDoTransaction');
    });
    
    Route::get('/get-all-report-type',[Android::class,'getAllReportType'])->name('getAllReportType');
    Route::post('/get-report',[Android::class,'getReport'])->name('getReport');
    Route::post('/get-report-by-search',[Android::class,'getReportByKey'])->name('getReportByKey');
    Route::get('/get-commission-plans',[Android::class,'commissionPlans'])->name('commissionPlans');
    #wallet to wallet
    Route::prefix('wallet-to-wallet')->group(function(){
        Route::post('fetch-by-number',[Android::class,'fetchUserByNumber'])->name('fetchUserByNumber');
        Route::post('do-transactions',[Android::class,'walletTowalletDoTransactions'])->name('walletTowalletDoTransactions');
    });
    
    #dmt  
    Route::prefix('dmt')->group(function(){
        Route::post('/login',[Android::class,'dmtLogin'])->name('dmtLogin');
        Route::post('/register',[Android::class,'dmtRegister'])->name('dmtRegister');
        Route::post('/data',[Android::class,'dmtData'])->name('dmtData');
        Route::get('/bank-list',[Android::class,'dmtBankList'])->name('dmtBankList');
        Route::post('/add-bank-account',[Android::class,'dmtAddAccount'])->name('dmtAddAccount');
        Route::post('/delete-bank-account',[Android::class,'dmtDeleteAccount'])->name('dmtDeleteAccount');
        Route::post('/initiate-transaction',[Android::class,'dmtInitiateTransaction'])->name('dmtInitiateTransaction');
        Route::post('/do-transaction',[Android::class,'dmtDoTransactions'])->name('dmtDoTransactions');
        Route::post('/logout',[Android::class,'dmtLogout'])->name('dmtLogout');
    });
    
    #uti  
    Route::prefix('uti')->group(function(){
        Route::get('check-status',[Android::class,'utiCheckStatus'])->name('utiCheckStatus');
        Route::post('register',[Android::class,'utiRegister'])->name('utiRegister');
        Route::post('buy-coupon',[Android::class,'buyUtiCoupon'])->name('buyUtiCoupon');
    });
    #support 
    Route::prefix('support')->group(function(){
        Route::get('/supprt-type',[Android::class,'getAllsupportType'])->name('getAllsupportType');
        Route::post('/create-ticket',[Android::class,'registerAllsupportType'])->name('registerAllsupportType');

    });
    #logout 
    Route::get('/user-logout',[Android::class,'logout'])->name('logout');
    
    #other services
    Route::prefix('other-service')->controller(MainController::class)->group(function (){
        Route::post('pan_verification','pan_verification_details')->name('verifyPan');
    });
    
    #subscription 
    Route::controller(BbpsController::class)->prefix('subscription')->group(function (){
        Route::get('/{type}','subscription')->name('subscription.index');
        Route::get('/{type}/{id}/{name}','get_details')->name('subscription.get_details');
        Route::post('/fetch_bill','subscription_fetch_bill')->name('subscription.fetch_bill');
        Route::post('/pay_bill','subscription_pay_bill')->name('subscription.paybill');
    });
});



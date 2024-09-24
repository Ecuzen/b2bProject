<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Callback extends Controller
{
    public function recharge()
    {
        $response = file_get_contents('php://input');
        $response = json_decode($response);
        if(!isset($response->status))
        return false;
        if($response->status == null || $response->status == "")
        return true;
        $response->status = strtoupper($response->status);
        #find txn id
        $checkTxn = DB::table('rechargetxn')->where('txnid',$response->txnid)->count();
        if($checkTxn <= 0)
        return false;
        if($response->status == 'SUCCESS')
        {
            #update the txn to success
            $updateData = array(
                'status' => $response->status,
                'response' => json_encode($response)
                );
            DB::table('rechargetxn')->where('txnid',$response->txnid)->update($updateData);
        }
        else if($response->status == 'FAILED')
        {
            #update the txn to success
            $updateData = array(
                'status' => $response->status,
                'response' => json_encode($response)
                );
            DB::table('rechargetxn')->where('txnid',$response->txnid)->update($updateData);
            #check already commission given or not
            $checkPreviousRefund = DB::table('wallet')->where('txnid',$response->txnid)->where('type','RECHARGE-REFUND')->count();
            if($checkPreviousRefund > 0)
            return true;
            #find all txn commision
            $allTxn = DB::table('wallet')->where('txnid',$response->txnid)->get();
            foreach ($allTxn as $txn)
            {
                if($txn->type == 'RECHARGE')
                $this->creditEntry($txn->uid,'RECHARGE-REFUND',$response->txnid,$txn->amount);
                if($txn->type == 'RECHARGE-COMMISSION')
                $this->debitEntry($txn->uid,'RECHARGE-COMMISSION-R',$response->txnid,$txn->amount);
            }
        }
    }
    public function dmt()
    {
        $response = file_get_contents('php://input');
        DB::table('test')->insert(['data'=>$response]);
    }
    public function dmtAccountVerify()
    {
        $response = file_get_contents('php://input');
        DB::table('test')->insert(['data'=>$response]);
    }
    public function quickTransferCallBack()
    {
        $response = file_get_contents('php://input');
        $response = json_decode($response);
        if(!isset($response->status))
        return response()->json(["status_code"=>0]);
        if($response->status == null || $response->status == "")
        return response()->json(["status_code"=>0]);
        $response->status = strtoupper($response->status);
        if(!isset($response->info))
        return response()->json(["status_code"=>0]);
        $txnDetails = $response->info;
        #find txn id
        $checkTxn = DB::table('qtransfertxn')->where('txnid',$txnDetails->txnid)->orWhere('txnid',$txnDetails->user_refkey)->first();
        if($checkTxn == "" || $checkTxn == null || count((array)$checkTxn) <= 0)
        return response()->json(["status_code"=>0]);
        $notDoAnyThing = ['SUCCESS','success','Success','PENDING','pending','Pending'];
        $do = ['Failed','FAILED','failed','ERROR','Error','error','FAILURE','Failure','failure'];
        if(in_array($response->status,$notDoAnyThing))
        {
            if(in_array($txnDetails->status,$notDoAnyThing))
            {
                #check if already
                if($txnDetails->status != $checkTxn->status)
                {
                    $updateData = array(
                        'status' => $txnDetails->status,
                        'message' => $response->msg??$txnDetails->msg,
                        'callback' => json_encode($response)
                        );
                    $this->updateData('qtransfertxn','txnid',$txnDetails->txnid,$updateData);
                    
                    return response()->json(["status_code"=>1]);
                }
                return response()->json(["status_code"=>2]);
            }
            else if(in_array($response->status,$do))
            {
                if($txnDetails->status != $checkTxn->status)
                {
                    $updateData = array(
                        'status' => $txnDetails->status,
                        'message' => $response->msg??$txnDetails->msg,
                        'callback' => json_encode($response)
                        );
                    $this->updateData('qtransfertxn','txnid',$txnDetails->txnid,$updateData);
                    # find all wallet transactions
                    $walletTransactions = DB::table('wallet')->where('txnid',$txnDetails->txnid)->orWhere('txnid',$txnDetails->user_refkey)->get();
                    $refundType = ['QT-Charge','Q-Transfer'];
                    $revert = ['QT-Commission'];
                    foreach ($walletTransactions as $single)
                    {
                        if(in_array($single->type,$refundType))
                        {
                            $uid = $single->uid;
                            $amount = $single->amount;
                            $this->creditEntry($uid,'QT-REFUND',$txnDetails->txnid??$txnDetails->user_refkey,$amount);
                        }
                        if(in_array($single->type,$revert))
                        {
                            $uid = $single->uid;
                            $amount = $single->amount;
                            $this->debitEntry($uid,'QT-COMM-R',$txnDetails->txnid??$txnDetails->user_refkey,$amount);
                        }
                    }
                    
                    return response()->json(["status_code"=>1]);
                }
                return response()->json(["status_code"=>2]);
            }
            else
            return response()->json(['status_code'=>0]);
        }
        else if(in_array($response->status,$do))
        {
            if($txnDetails->status != $checkTxn->status)
            {
                $updateData = array(
                    'status' => $txnDetails->status,
                    'message' => $response->msg??$txnDetails->msg,
                    'callback' => json_encode($response)
                    );
                $this->updateData('qtransfertxn','txnid',$txnDetails->txnid,$updateData);
                # find all wallet transactions
                $walletTransactions = DB::table('wallet')->where('txnid',$txnDetails->txnid)->orWhere('txnid',$txnDetails->user_refkey)->get();
                $refundType = ['QT-Charge','Q-Transfer'];
                $revert = ['QT-Commission'];
                foreach ($walletTransactions as $single)
                {
                    if(in_array($single->type,$refundType))
                    {
                        $uid = $single->uid;
                        $amount = $single->amount;
                        $this->creditEntry($uid,'QT-REFUND',$TxnDetails->txnid??$txnDetails->user_refkey,$amount);
                    }
                    if(in_array($single->type,$revert))
                    {
                        $uid = $single->uid;
                        $amount = $single->amount;
                        $this->debitEntry($uid,'QT-COMM-R',$TxnDetails->txnid??$txnDetails->user_refkey,$amount);
                    }
                }
                
                return response()->json(["status_code"=>1]);
            }
            return response()->json(["status_code"=>2]);
        }
        else
        return response()->json(['status_code'=>0]);
    }
    public function payoutCallback()
    {
        $response = file_get_contents('php://input');
        // $response = '{"status":"FAILED","txnid":"PYOT329187252749337","amount":15000,"rrn":"NA","msg":"Transaction Failed","mode":"IMPS"}';
        $response = json_decode($response);
        if(!isset($response->status))
        return false;
        if($response->status == null || $response->status == "")
        return true;
        $response->status = strtoupper($response->status);
        #check for txn
        $checkTxn = DB::table('payouttxn')->where('txnid',$response->txnid)->count();
        if($checkTxn <= 0)
        return false;
        $notDoAnyThing = ['SUCCESS','success','Success','PENDING','pending','Pending','IN_PROCESS','accepted','ACCEPTED'];
        $do = ['Failed','FAILED','failed','ERROR','Error','error','FAILURE','Failure','failure'];
        if(in_array($response->status,$notDoAnyThing))
        {
            #update the txn to success
            $updateData = array(
                'status' => $response->status,
                'response' => json_encode($response),
                'message' => $response->msg??'Transaction is '.strtolower($response->status)
                );
            DB::table('payouttxn')->where('txnid',$response->txnid)->update($updateData);
        }
        else if(in_array($response->status,$do))
        {
            #update the txn to success
            $updateData = array(
                'status' => $response->status,
                'response' => json_encode($response),
                'message' => $response->msg??'Transaction is '.strtolower($response->status)
                );
            DB::table('payouttxn')->where('txnid',$response->txnid)->update($updateData);
            #check already commission given or not
            $checkPreviousRefund = DB::table('wallet')->where('txnid',$response->txnid)->where('type','PAYOUT-REFUND')->count();
            if($checkPreviousRefund > 0)
            return false;
            #find all txn commision
            $allTxn = DB::table('wallet')->where('txnid',$response->txnid)->get();
            $refundType = ['Payout','Payout-charge'];
            $revert = ['PYT-Commission'];
            foreach ($allTxn as $txn)
            {
                if(in_array($txn->type,$refundType))
                {
                    if($txn->type == 'Payout')
                    {
                        $this->creditEntry($txn->uid,'PAYOUT-REFUND',$response->txnid,$txn->amount);
                    }
                    else
                    {
                        $this->creditEntry($txn->uid,'PAYOUT-CHARGE-REFUND',$response->txnid,$txn->amount);
                    }
                }
                else if(in_array($txn->type,$revert))
                {
                    $this->debitEntry($txn->uid,'PAYOUT-COMMISSION-R',$response->txnid,$txn->amount);
                }
            }
        }
    }
    public function ecuzenPgCallback()
    {
        $response = file_get_contents('php://input');
        // $response = '{"status":"SUCCESS","msg":"Collect request status has been updated successfully","data":{"sid":"ECZ906325","type":"COLLECT-REQ","txnid":"UPICR6516C02728503","amount":1,"payer_name":"Aman","payer_vpa":"8696067701@ybl","expiry":"2023-09-29T17:47:39.000000Z","remark":"AadharSee","status":"SUCCESS","txntime":null,"qrid":null,"created_at":"2023-09-29T12:16:39.000000Z"}}';
        // DB::table('test')->insert(['data'=>$response]);
        $response = json_decode($response);
        if(!isset($response->status))
        return false;
        if($response->status == 'SUCCESS')
        {
            $txnData = $response->data;
            // return $txnData->txnid;
            if($txnData->type == 'COLLECT-REQ')
            {
                #find txnid 
                $getTxnRow = DB::table('pgTxn')->where('txnid',$txnData->txnid)->first();
                if($getTxnRow == "" || $getTxnRow == null)
                return false;
                if($getTxnRow->status != 'PENDING')
                return false;
                # data to be updated 
                $this->updateData('pgTxn','txnid',$txnData->txnid,['status'=>$txnData->status,'callback'=>json_encode($response)]);
                $user = DB::table('users')->where('id',$getTxnRow->uid)->first();
                $charge = $this->checkcharge($txnData->amount,'pg_charges',$user->package);
                if($charge != true)
                $charge = 0;
                if($txnData->status == 'SUCCESS')
                {
                    $this->creditEntry($getTxnRow->uid,'UPI-TXN',$txnData->txnid,$txnData->amount);
                    $this->debitEntry($userId,'UPI-TXN-CHARGE',$txnData->txnid,$charge);
                }
                return true;
            }
            elseif ($txnData->type == 'UPI-QR') {
                $userId = str_replace('USERID','',$txnData->qrid);
                $user = DB::table('users')->where('id',$userId)->first();
                $charge = $this->checkcharge($txnData->amount,'pg_charges',$user->package);
                if($charge != true)
                $charge = 0;
                #check txnid already exist
                $checkTxn = DB::table('pgTxn')->where('txnid',$txnData->txnid)->count();
                if($checkTxn > 0)
                return false;
                $insertdata = array(
                    'uid' => $user->id,
                    'sid' => $txnData->sid,
                    'txnid' => $txnData->txnid,
                    'type' => $txnData->type,
                    'amount' => $txnData->amount,
                    'name' => $txnData->payer_name,
                    'vpa' => $txnData->payer_vpa,
                    'status' => $txnData->status,
                    'message' => $response->msg,
                    'response' => null,
                    'callback' => json_encode($response),
                );
                DB::table('pgTxn')->insert($insertdata);
                if($txnData->status == 'SUCCESS')
                {
                    $this->creditEntry($userId,'UPI-TXN',$txnData->txnid,$txnData->amount);
                    $this->debitEntry($userId,'UPI-TXN-CHARGE',$txnData->txnid,$charge);
                }
                return true;
            }
        }
        else
        return false;
    }
    
    public function ecuzenPg()
    {
      
        $response = file_get_contents('php://input');
        // $response = '{"status":"SUCCESS","msg":"Collect request status has been updated successfully","data":{"sid":"ECZ906325","type":"COLLECT-REQ","txnid":"UPICR6516C02728503","amount":1,"payer_name":"Aman","payer_vpa":"8696067701@ybl","expiry":"2023-09-29T17:47:39.000000Z","remark":"AadharSee","status":"SUCCESS","txntime":null,"qrid":null,"created_at":"2023-09-29T12:16:39.000000Z"}}';
         DB::table('test')->insert(['data'=>json_encode($response)]);
        
       
        
    }
}

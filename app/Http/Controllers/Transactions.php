<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\User;

class Transactions extends Controller
{
    public function __construct()
    {
        if(session('uid') == null)
        {
            return redirect()->to('/');
        }
    }
    public function index(Request $res,$type)
    {
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $data = ['title'=>$title,'logo'=>$logo];
        $data['type'] =$type;
        switch($type)
        {
            case 'aeps' :
                $data['header'] = 'AEPS Transactions History';
                return view('transactions.transactions',$data);
                break;
            case 'money-transfer' :
                $data['header'] = 'DMT Transactions History';
                return view('transactions.transactions',$data);
                break;
            case 'qtransfer' :
                $data['header'] = 'Quick Transfer Transactions History';
                return view('transactions.transactions',$data);
                break;
            case 'payout' :
                $data['header'] = 'Payout Transactions History';
                return view('transactions.transactions',$data);
                break;
            case 'recharge' :
                $data['header'] = 'Recharge Transactions History';
                return view('transactions.transactions',$data);
                break;
            case 'bbps' :
                $data['header'] = 'BBPS Transactions History';
                return view('transactions.transactions',$data);
                break;
            case 'uti-coupan' :
                $data['header'] = 'Uti Coupan Transactions History';
                return view('transactions.transactions',$data);
                break;
            case 'wallet' :
                $data['header'] = 'Wallet Transactions History';
                return view('transactions.transactions',$data);
                break;
            case 'wallet-to-wallet' :
                $data['header'] = 'Wallet To Wallet Transactions History';
                return view('transactions.transactions',$data);
                break;
            default:
                return view('errors.404');
            
        }
    }
    public function fetch(Request $res,$type)
    {
        $uid = session('uid');
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
                $txnD = DB::table('iaepstxn')->whereBetween('date', [$from, $to])->where('uid',$uid)->orderBy('id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('status', $txnType);
                }
                $txnData = $txnD->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                foreach ($txnData as $tData)
                {
                    $tData->bank = DB::table('banks')->where('id',$tData->bank)->first()->name;
                    if($tData->type == 'CW' || $tData->type == 'AP')
                    $tData->amount = $tData->txnamount;
                }
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('transactions.type.aeps',['txnData'=>$txnData])->render()]);
                break;
            case 'money-transfer' :
                $txnD = DB::table('dmrtxn')->whereBetween('date', [$from, $to])->where('uid',$uid)->orderBy('id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('status', $txnType);
                }
                $txnData = $txnD->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('transactions.type.dmt',['txnData'=>$txnData])->render()]);
                break;
            case 'qtransfer' :
                $txnD = DB::table('qtransfertxn')->whereBetween('date', [$from, $to])->where('uid',$uid)->orderBy('id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('status', $txnType);
                }
                $txnData = $txnD->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('transactions.type.qtransfer',['txnData'=>$txnData])->render()]);
                break;
            case 'payout' :
                $txnD = DB::table('payouttxn')->whereBetween('date', [$from, $to])->where('uid',$uid)->orderBy('id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('status', $txnType);
                }
                $txnData = $txnD->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('transactions.type.payout',['txnData'=>$txnData])->render()]);
                break;
            case 'recharge' :
                $txnD = DB::table('rechargetxn')->whereBetween('date', [$from, $to])->where('uid',$uid)->orderBy('id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('status', $txnType);
                }
                $txnData = $txnD->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                foreach ($txnData as $tData)
                {
                    $tData->operator = DB::table('rechargeop')->where('id',$tData->operator)->first()->name;
                }
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('transactions.type.recharge',['txnData'=>$txnData])->render()]);
                break;
            case 'bbps' :
                $txnD = DB::table('bbpstxn')->whereBetween('date', [$from, $to])->where('uid',$uid)->orderBy('id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('status', $txnType);
                }
                $txnData = $txnD->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                foreach ($txnData as $tData)
                {
                    $tData->boperator = DB::table('bbpsnewop')->where('id',$tData->boperator)->first()->name;
                }
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('transactions.type.bbps',['txnData'=>$txnData])->render()]);
                break;
            case 'uti-coupan' :
                $txnD = DB::table('pancoupontxn')->whereBetween('date', [$from, $to])->where('uid',$uid)->orderBy('id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('status', $txnType);
                }
                $txnData = $txnD->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('transactions.type.uti',['txnData'=>$txnData])->render()]);
                break;
            case 'wallet' :
                $txnD = DB::table('wallet')->whereBetween('date', [$from, $to])->where('uid',$uid)->orderBy('id', $orderType);
                if($txnType != '1')
                {
                    $txnD->where('txntype', $txnType);
                }
                $txnData = $txnD->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('transactions.type.wallet',['txnData'=>$txnData])->render()]);
                break;
            case 'wallet-to-wallet' :
                $txnData = DB::table('wallet_to_wallet')->whereBetween('date', [$from, $to])->where('sender',$uid)->orWhere('receiver',$uid)->orderBy('id', $orderType)->get();
                if($txnData->count() == 0)
                return response()->json(['status'=>'SUCCESS','message'=>'No Transaction found!!','view'=>""]);
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
                return response()->json(['status'=>'SUCCESS','message'=>'Transactions found!','view'=>view('transactions.type.walletToWallet',['txnData'=>$txns])->render()]);
                break;
            default:
                return view('errors.404');
        }
        return $txnData;
    }
}

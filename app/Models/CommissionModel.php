<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommissionModel extends Model
{
    
    public function commission($table,$owner,$txnid,$amount,$uid,$charge,$type)
    {
        while($owner != 0)
        {
            $ownerrow = DB::table('users')->where('id', $owner)->first();
            if($ownerrow != "")
            {
                $package = $ownerrow->package;
                $commission = DB::table($table)->where('package',$package)->where('froma','<=',$amount)->where('toa','>=',$amount)->first();
                if($commission == ""){
                    if($owner == $ownerrow->owner)
                    {
                        break;
                    }
                    $owner = $ownerrow->owner;
                    $package = $ownerrow->package;
                }
                
                if($commission->percent == 1)
                {
                    $ownercommission = ($commission->amount * $amount) / 100;
                }else{
                    $ownercommission = $commission->amount;
                }
                $netcommission = $charge - $ownercommission;
                $commissionEntry = array(
                    'uid' => $ownerrow->id,
                    'type' => $type,
                    'txn_uid' => $uid,
                    'txntype' => 'CREDIT',
                    'txnid' => $txnid,
                    'amount' => $netcommission,
                    'opening' => $ownerrow->wallet,
                    'closing' => $ownerrow->wallet + $netcommission
                    );
                    DB::table('wallet')->insert($commissionEntry);
                    DB::table('users')->where('id',$ownerrow->id)->update(['wallet',$ownerrow->wallet + $netcommission]);
                    if($owner == $ownerrow->owner)
                    {
                        break;
                    }
                    $owner = $ownerrow->owner;
                    $package = $ownerrow->package;
            }else{
                break;
            }
            
        }
        return true;
    }
    
    
}

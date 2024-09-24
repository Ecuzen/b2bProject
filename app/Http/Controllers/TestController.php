<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
        $commission_owner=[];
        $owners_id =[];
        $userDetails = DB::table("users")->where('id',9)->first();
            $userPackage=$userDetails->package;
            $baseId = $userDetails->id;
        $amount = 1400;
        if ($userPackage) {
            $com = DB::table("qtransfer")
                ->where("package", $userPackage)
                ->where('froma',"<=",$amount)
                ->where('toa',">=",$amount)
                ->first();
            if($com->percent == 1){
            $commission = $com==null?0:$amount * ($com->amount/100);
            }
            else{
                $commission = $com->amount;
            }
            //$id = $com->id;
            if ($commission !=null) {
                $id = 9;
                while ($id != 0) {
                    $ownerDeatils = DB::table("users")->where('id',$id)->first();
                       $owner = $ownerDeatils->owner;
                    if ($owner != 0) {
                        $owners_id[] = DB::table("users")->select("owner")->where("id", $id)->value("owner");
                        $package_Of_Owner = DB::table("users")
                            ->select("package")
                            ->where("id", $owner)
                            ->value("package");
                           
                         $com2 = DB::table("qtransfer")
                                ->where("package", $package_Of_Owner)
                                ->where('froma',"<=",(int)$commission)
                                ->where('toa',">=",(int)$commission)
                                ->first();
                    if($com2->percent ==1){
                        $commission_owner[] = $com2==null?0:$commission * ($com2->amount/100);
                    }
                    else{
                         $commission_owner[] = $com2->amount;
                    }
                  } $id = DB::table("users")->select("owner")->where("id", $id)->value("owner");
                }//loop end
                dd($baseId,$commission,$owners_id,$commission_owner,$id);
            }
        }
    }
    public function externalResponse()
    {
       
        $txnid = "Test".rand(0000000000,9999999999);
        $token = 'cc59ab4f07595b8935317b1b54ab1a';
        $mid = 'IN1596494071';
        $data = [
            "utxnid" => $txnid,
            "token" => $token,
            "mid" => $mid
        ];
        $jsonBody = json_encode($data);
        $base64Body = base64_encode($jsonBody);
        $encryptionKey = "mid"; 
        $iv = rand(1111111111111111,9999999999999999);
        $encryptedBody = openssl_encrypt($base64Body, 'AES-256-CBC', $encryptionKey, 0, $iv);
        $hashInput = $token . $mid . $txnid;
        $sha512Hash = hash('sha512', $hashInput);
        $requestData = [
            "body" => $encryptedBody,
            "hash" => $sha512Hash
        ];
        $requestJson = json_encode($requestData);
        // return $requestJson.$iv;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://indicpay.in/api/upi/fetch_qr',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$requestJson,
          CURLOPT_HTTPHEADER => array(
            'iv: '.$iv,
            'token: '.$token,
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
        
            }
}

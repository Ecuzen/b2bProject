<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TestTwoController extends Controller
{
    
    public function dataGet(){
        
        
        $data_commision = [];
        $owner_id = [];
        $users = DB::table('users')->where('id',9)->first();
        //dd($users);
        $package1_data = $users->package;
        $ownerId  = $users->id;
       
        //$data = DB::table('qtransfer')->where('package',$package1_data)->get();
        //dd($data);
        $dummy_amount = 1200;
        if($package1_data){
        $manage = DB::table('qtransfer')->where('package',$package1_data)
                                                  ->where('froma', '<=',$dummy_amount )
                                                    ->where('toa','>=',$dummy_amount)->first();
                                                    //dd($manage);
                  //  = $dummy_amount->amount;         
                  if($manage->percent ==1){
                  $data_com = $manage == null ?0:$dummy_amount * ($manage->amount/100);
                  }else{
                      $data_com = $manage->amount;
                  }
        
           
            $id = 9;
            
            while($id != 0){
            $commssion_data = DB::table('users')->where('id',$id)->first();
            //dd($commssion_data);
            $owner = $commssion_data->owner;
            //dd($owner);
            //dd($commssion_data);
            if($owner != 0 ){
               $package_data[] =     DB::table('users')->select('owner')->where('id',$id)->value('owner');
               $package_query = DB::table('users')->select('package')->where('id', $owner)->value('package');
               //dd($package_query);
                //dd($package_data);
                $transfer = DB::table('qtransfer')->where('package',$package_query)
                                                  ->where('froma', '<=',$data_com )
                                                    ->where('toa','>=',$data_com)->first();
                if($transfer->percent == 1){
                 
                  $data_commision[] = $transfer == null ?0:($data_com * $transfer->amount)/100; 
                    
                  //dd($data_commision);
                }else{
                
                     $data_commision[] = $transfer->amount;
                }
                  
                 

            }$user_manage = DB::table('users')->select('owner')->where('id',$owner_id)->value('owner');
               dd($owner_id,$data_commision,$data_com,$package_data);
                
            }            
            }


    }

           
        
        
    }

//         $id = 9;
//         while($id != 0)
//         $commssion_data = DB::table('users')->select('owner')->where('id',$id)->value('owner');
//         dd($commssion_data);
//         if($commssion_data != 0 ){
            
//          $package_data =     DB::table('users')->select('package')->where('id',$commssion_data)->value('package');
//          dd($package_data);
//          $transfer = DB::table('qtransfer')->where('package',$package_data)->get();
         
//           foreach ($transfer as $value){
            
//             $form_data = $value->froma;
           
//             $to_data = $value->toa;
//              //dd($to_data);
//             if($dummy_amount > $form_data && $dummy_amount < $to_data){
                
//             if($value->percent == 1){
             
//              $amount = $value->amount;
//              //dd($amount);
//              $data_commision[]  = $dummy_amount*$amount/100;
//              dd($data_commision);
//             }else{
//                 $data_commision[] = $value->amount;
//                 //dd($data_commision);
//             }
//               $user_manage = DB::table('users')->select('owner')->where('id',$users)->value('owner');
           
//           dd($user_manage);
                
//             }
               
//           }
//         }
         
         
       
//     }
    
// }

            
                
//             }
        
        
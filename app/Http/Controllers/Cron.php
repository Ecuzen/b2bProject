<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class Cron extends Controller
{
    public function cronNotify()
    {
        try
        {
            // exit;
            
            $notificationData = DB::table('notification_sent_records')->where('sending_status',0)->where('status',0)->get()->toArray();
            /*echo '<pre>';
            print_r($notificationData);
            die;*/
            
            foreach($notificationData as $notification)
            {

                
                DB::table('notification_sent_records')->where('id',$notification->id)->update(['sending_status' => 1]);

                $all_message = json_decode($notification->message);
                // echo '<pre>';
                // print_r($all_message);
                // die;

                if(empty($all_message))
                {
                    DB::table('notification_sent_records')->where('id',$notification->id)->update(['status' => 1]);
                }

                $total_user_ids = explode(",",$notification->user_ids);

                if(!empty($notification->sent_user_ids))
                {
                    $total_sent_user_ids = explode(",",$notification->sent_user_ids);
                }
                else
                {
                    $total_sent_user_ids = array();
                }

                $PfplaArgument = array();
                $UserNotifications = DB::table('user_notifications')->get();
                $kp = 1;

                foreach($all_message as $key => $all_message_data)
                {

                    if($key <= 30)
                    {

                        $total_sent_user_ids[] = $all_message_data->notification_data->user_id;
                        $androiddata = (array)$all_message_data->notification_data;

                        $userNotifications_data = DB::table('user_notifications')->where('model','adminNotify')->where('user_id',$androiddata['user_id'])->where('related_id',$notification->notification_id)->orderBy("id","DESC")->get();

                        if ( $userNotifications_data->count() == 0)
                        {
                            $sender_user_id = 1;
                            $receiver_user_id = $androiddata['user_id'];

                            $dataArr = array(
                                "url" => NULL,
                                "model" => "adminNotify",
                                "title" => $androiddata['title'],
                                "title_en" => $androiddata['title'],
                                "status" => 0,
                                "user_id" => $receiver_user_id,
                                "created" => date("Y-m-d H:i:s"),
                                "modified" => date("Y-m-d H:i:s"),
                                "to_user_id" => $receiver_user_id,
                                "related_id" => $notification->notification_id,
                                "device_type" => 'Android',
                                "notification" => $androiddata['message'],
                                "form_user_id" => $sender_user_id,
                                "notification_en" => $androiddata['message'],
                            );

                            DB::table('user_notifications')->insert($dataArr);
                            $receiver_user_id = $androiddata['user_id'];
                        }

                        $token = $all_message_data->device_token;

                        if(!empty($token))
                        {
                            $this->pushAndroid($token, $androiddata);
                        }
                    }

                    if($key >= 31)
                    {
                        $data = $all_message_data->notification_data;
                        $token = $all_message_data->device_token;
                        $PfplaArgument[] = array( 'device_type' => "Android", "device_token" => $token, "notification_data" => $data );
                        DB::table('notification_sent_records')->where('id',$notification->id)->update(['sending_status' => 1]);
                        break;
                    }

                    unset($all_message[$key]);
                    $kp++;
                }

                if(!empty($total_sent_user_ids))
                {
                    $all_message = array_values($all_message);
                    DB::table('notification_sent_records')->where('id',$notification->id)->update(['message' => json_encode($all_message, JSON_UNESCAPED_UNICODE),'sent_user_ids' => implode(",",array_unique($total_sent_user_ids))]);
                
                }

                if(count($total_user_ids) == count(array_unique($total_sent_user_ids)))
                {
                    DB::table('notification_sent_records')->where('id',$notification->id)->update(['status' => 1]);
                }
            }
        }
        catch (Exception $e)
        {
            die();
        }
        catch (\PDOException  $e)
        {
            die();
        }

        echo "📨 Send"; die;
    }
}

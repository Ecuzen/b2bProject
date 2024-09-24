<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Notifications extends Controller
{
    protected function common()
    {
        $title = DB::table('settings')->where('name', 'title')->first()->value;
        $logo = DB::table('settings')->where('name', 'logo')->first()->value;
        $dName = $wordsArray = explode(' ', $title);
        $dName = $wordsArray[0];
        return array('logo' => $logo,'title' => $title,'dName' => $dName);
    }
    public function index()
    {
        try 
        {
            $users = User::pluck('name','id');
            $data = $this->common();
            $data['tname'] = 'Notifications';
            $data['users'] = $users;
            return view('admin.notifications.index',$data);
        }
        catch(\Exception $e)
        {
            return redirect()->route('admin')->with('error',$e->getMessage());
        }
        catch(\PDOException $e)
        {
            return redirect()->route('admin')->with('error',$e->getMessage());
        }
    }
    
    public function store(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'url' => 'required',
                'title' => 'required',
                'message' => 'required',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(['status'=>'ERROR','message'=>$errors->first(),'info' => $errors], 200);
            }
            $user_type = request('role');
            $insertData = array(
                'link' => request('url'),
                'title' => request('title'),
                'role_id' => request('role'),
                'description' => request('message'),
                );
            $create = Notification::create($insertData);
            if ($create)
            {
                switch ($user_type) 
                {
                    case '1':
                        $Query =  DB::table('app')->join('users', 'users.id', '=', 'app.uid')->get();
                    break;
                    
                    case '2':
                        $requestUser = request('users');
                        if(!empty($requestUser))
                        {
                            $Query =  DB::table('app')->join('users', 'users.id', '=', 'app.uid')->whereIntegerInRaw('app.uid',$requestUser)->get();
                        }
                    break;
                }
                if(!empty($Query))
                {
                    foreach ($Query as $UserDeviceData)
                    {
                        $token = $UserDeviceData->device_token;
                        $user_id = $UserDeviceData->uid;
                        $device_type = $UserDeviceData->device_type;
    
                        if(!empty($token))
                        {
                            $all_get_notifications_user_id[] = $user_id;
                            $data = [
                                        'link' => request('url'),
                                        'title' => request('title'),
                                        'message' => request('message'),
                                        'user_id' => $user_id,
                                        'item_id' => 0,
                                        'notify_type' => 'adminNotify'
                                    ];
                            if(strtolower($UserDeviceData->device_type) == "android")
                            {
                                $PfplaArgument[] = array('device_type' => "Android","device_token" => $token,"notification_data" => $data);
                            }
                        }
                    }
    
                    if(!empty($all_get_notifications_user_id))
                    {
                        $getMessage = json_encode($PfplaArgument, JSON_UNESCAPED_UNICODE);
                        $getuserIds = implode(",",array_unique($all_get_notifications_user_id));
    
                        DB::table('notification_sent_records')->insert([
                            'status' => false,
                            'message' => $getMessage,
                            'user_ids' => $getuserIds,
                            'notification_id' => $create->id,
                        ]);
                    }
                }
            }
            return  response()->json(['status'=>'SUCCESS','message'=>'Notification created successfully']);
        }
        catch (PDOException $e)
        {
            return back()->with('error',$e->getMessage());;
        }
    }
    
}

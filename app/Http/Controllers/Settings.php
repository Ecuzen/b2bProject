<?php
namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Traits\{Call_Api_Trait,File_Upload_Trait};
use DB;
class Settings extends Controller
{
    use Call_Api_Trait,File_Upload_Trait;
    public function File_Uploads(Request $request)
    {
        try
        {
            $AuthUser = $userData = $request->user();
            if(isset($AuthUser) && !empty($AuthUser))
            {
                $userID = $AuthUser->id;
                $validator = Validator::make($request->all(), [
                    'file_name' => 'required',
                    'file_type' => 'required|in:Image,Pdf',
                ]);

                if ($validator->fails())
                {
                    return response()->json(['message' => $validator->errors()->first()],401);
                }
            
                $headers = [
                    "headerKey:".$this->Get_Vs_Api_Key(),
                    "ipAddress:".$request->ip(),
                ];
                
                $fileType = $request->file_type;
                
                $url = "https://vyaparsamadhan.co.in/api/public-api/v1/settings/file-uploads";
                $files = $request->allFiles();
                
                $responseData = $this->Vs_Call_Api_With_File($url, $request->all(), $headers ,$files);
                $responseData = json_decode($responseData);
                // echo '<pre>';
                // print_r($responseData);
                // die;
                if($responseData->status_code == 200)
                {
                    $file_name = $responseData->data->file_name;
                    if ($request->hasFile('file_name'))
                    {
                        $this->Upload_File($request->file('file_name'),'users',$fileType,$userID,$file_name);
                    }
                }
                return response()->json($responseData,$responseData->status_code);
            }
            else
            {
                return response()->json(['message' => "Oops an error has occurred! Please try again (Error code: Authentification Error)"],401);
            }
        }
        catch (\PDOException $e)
        {
            return response()->json(['message' => $e->getMessage()],401);
        }
        catch (Exception $e)
        {
            return response()->json(['message' => $e->getMessage()],401);
        }
    }
    
    public function Web_File_Uploads(Request $request)
    {
        try
        {  
            // echo '<pre>';
            // print_r($request->all());
            // die;
            $uid = session('uid');
            
            // $AuthUser = Auth::user();
            $AuthUser =  DB::table('users')->where('id',$uid)->first();
            $userID = $AuthUser->id??0;
            $validator = Validator::make($request->all(), [
                'file_name' => 'required',
                'file_type' => 'required|in:Image,Pdf',
            ]);

            if ($validator->fails())
            {
                return response()->json(['message' => $validator->errors()->first()],401);
            }
        
            $headers = [
                "headerKey:".$this->Get_Vs_Api_Key(),
                "ipAddress:".$request->ip(),
            ];
            
            $fileType = $request->file_type;
            
            $url = "https://vyaparsamadhan.co.in/api/public-api/v1/settings/file-uploads";
            $files = $request->allFiles();
            
            $responseData = $this->Vs_Call_Api_With_File($url, $request->all(), $headers ,$files);
            $responseData = json_decode($responseData);
            if($responseData->status_code == 200)
            {
                $file_name = $responseData->data->file_name;
                if ($request->hasFile('file_name'))
                {
                    $this->Upload_File($request->file('file_name'),'users',$fileType,$userID,$file_name);
                }
            }
            return response()->json($responseData,$responseData->status_code);
            
        }
        catch (\PDOException $e)
        {
            return response()->json(['message' => $e->getMessage()],401);
        }
        catch (Exception $e)
        {
            return response()->json(['message' => $e->getMessage()],401);
        }
    }

}

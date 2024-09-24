<?php
namespace App\Traits;
trait Call_Api_Trait
{
    public function Vs_Call_Api_With_File($url, $data = [] , $headers = [], $files = [])
    {
        $curl = curl_init();

        $defaultHeaders = [
            "Accept: application/json",
        ];
        $postFields = $data;
        if (!empty($files))
        {
            foreach ($files as $key => $file)
            {
                if (is_array($file))
                {
                    foreach ($file as $index => $singleFile)
                    {
                        $postFields[$key . '[' . $index . ']'] = new CURLFile($singleFile->getPathname(), $singleFile->getMimeType(), $singleFile->getClientOriginalName());
                    }
                }
                else
                {
                    $postFields[$key] = new \CURLFile($file->getPathname(), $file->getMimeType(), $file->getClientOriginalName());
                }
            }
            // Content-Type must be multipart/form-data when uploading files
            $allHeaders = array_merge($defaultHeaders, $headers, ["Content-Type: multipart/form-data"]);
        }
        else
        {
            $allHeaders = array_merge($defaultHeaders, $headers, ["Content-Type: application/json"]);
            // $postFields = json_encode($data);
            $postFields = $data;
        }
        
        // Merge default headers with custom headers
        $allHeaders = array_merge($defaultHeaders, $headers);
        // echo '<pre>';
        // print_r($allHeaders);;die;
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 100,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => $allHeaders,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
        ]);

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION , true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    
    function Reffrence_Code($modelName, $prefixString = NULL , $fieldName = NULL)
    {
        if (empty($fieldName) || $fieldName == NULL)
        {
            $fieldName = "random_id";
        }

        $chars = "0A1B2C3D4E5F6G7H8I9J9K8L7M6N5O4P3Q2R1S0T0U1V2W3X4Y5Z6";
        $str = $prefixString.date('ym');
        for ($i = 0;$i < 4;$i++)
        {
            $str.= substr($chars, mt_rand(1, strlen($chars)), 1);
        }

        $check = \DB::table($modelName)->where($fieldName,$str)->orderBy('id','DESC')->count();
        if ($check > 0)
        {
            $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZZYXWVUTSRQPONMLKJIHGFEDCBA";
            $shuffledString = str_shuffle($string);
            $newPrefixString = strtoupper(mb_substr($shuffledString, 0, 2));
            $chars = "0A1B2C3D4E5F6G7H8I9J9K8L7M6N5O4P3Q2R1S0T0U1V2W3X4Y5Z6";
            $newString = $newPrefixString.date('ym');
            for ($i = 0;$i < 4;$i++)
            {
                $newString.= substr($chars, mt_rand(1, strlen($chars)), 1);
            }
            $str = $this->Reffrence_Code($modelName, $newString ,$fieldName);
        }
        return $str;
    }
    
    public function Get_Vs_Api_Key()
    {
        return $api_key = \DB::table('settings')->where('name','vs_api_key')->first()->value;
    }
    
    function rrmdir($dir)
    {
        if (is_dir($dir))
        {
            $objects = scandir($dir);
            foreach ($objects as $object)
            {
                if ($object != "." && $object != "..")
                {
                    if (filetype($dir."/".$object) == "dir")
                    {
                        $this->rrmdir($dir."/".$object);
                    }
                    else
                    {
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
        return true;
    }
}
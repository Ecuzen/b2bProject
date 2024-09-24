<?php
namespace App\Traits;
use File;
trait File_Upload_Trait
{
    public function Upload_File($request_file, $path, $file_type = "Image" , $user_id = NULL,$file_name = NULL)
    {
        if ($request_file->isValid())
        {
            File::ensureDirectoryExists(public_path('/uploads/'.$path));
            $fileName = ($file_name == NULL) ? $request_file->hashName() : $file_name;
            if ($user_id != NULL)
            {
                $destinationPath = public_path('/uploads/'.$path.'/'.$user_id.'/');
            }
            else
            {
                $destinationPath = public_path('/uploads/'.$path.'/');
            }
            $request_file->move($destinationPath, $fileName);
            return $fileName;
        }
        return null;
    }
}
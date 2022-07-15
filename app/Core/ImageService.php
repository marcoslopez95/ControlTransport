<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 8/1/18
 * Time: 10:01 AM
 */

namespace App\Core;


use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ImageService
{

    /**
     * @param $images
     * @param string $id
     * @return bool|string
     */
    public function image($images)
    {
        try{
            $route = rtrim(app()->basePath('public/'), '/') . "/images/";
            $route_web = '/images/';
            $now = Carbon::now()->format('Y-m-d');
            $upload_dir =$route;
            $img = $images;
            $ext = $this->get_extension($img);
            $img = str_replace('data:image/'.$ext.';base64,', '', $img);
            $data = base64_decode($img);
            $var_for = uniqid().'-'.$now. '.'.$ext;
            $file = $upload_dir . $var_for;
            $image = $route_web . $var_for;
            $success = file_put_contents($file, $data);
            return $success ?$image: false;
        }catch (\Exception $e){
            Log::critical($e->getMessage());
            return null;
        }

    }

    /**
     * @param $string
     * @return int
     * @throws Exception
     */
    public function get_extension($string)
    {
        $extension="";
        if(!empty($string)){
            $formats = ["jpg", "jpeg", "png", "gif",'icoñ..'];
            if(substr($string,0,4)=='http'){
                return $extension=3;
            }else {
                $data = $string;
                $pos = strpos($data, ';');
                $type = explode(':', substr($data, 0, $pos))[1];
                $extension = preg_split("[/]", $type);
                return $extension[1];
            }
        }else{
            return null;
        }
    }

    /**
     * permite eliminar una archivo en el directorio
     * @param String $document_path
     * @version 1.0
     */
    public function delete($document_path) {

        if (File::exists(app()->basePath('public'.$document_path))) {
            if(!unlink(app()->basePath('public'.$document_path))){
                return false;
            }
        }
        return true;
    }
}

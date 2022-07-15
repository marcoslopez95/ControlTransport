<?php

use Illuminate\Support\Facades\Log;

if(!function_exists('custom_response')){
    function custom_response($success = true, $message = 'Operación Exitosa', $data = [], $code = 200)
    {

        try{
            $count = count($data);
        }catch(\Exception $e){
            if(gettype($data) == 'string'){
                $count = 0;
            }
            $count = 1;
        }

        $json = [
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'count'   => $count
        ];
        return response()->json($json,$code);
    }
}

if(!function_exists('custom_error')){
    function custom_error(\Exception $e, $message = 'Error en la operación', $data = '',$code = 415){
        $logging = [
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
            'error' => $e->getMessage()
        ];
        if($data == '') $data = $e->getMessage();
        Log::info($logging);
        return custom_response(false,$message,$data,$code);
    }
}


if(! function_exists('custom_failed_validation')){
    function custom_failed_validation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = custom_response(false,'Error de validación',$validator->errors()->first(),422);

        throw new Illuminate\Validation\ValidationException($validator, $response);
    }
}

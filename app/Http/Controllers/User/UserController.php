<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;

/** @property UserService $service */
class UserController extends CrudController
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request){
        // if(Auth::user()->rol_id != 1){
            // return custom_response(false,'Desautorizado',[],401);
        // }
        return parent::_index($request);
    }

    public function show($id,Request $request){
        // if(Auth::user()->rol_id != 1 && Auth::user()->id != $id){
            // return custom_response(false,'Desautorizado',[],401);
        // }
        return parent::_show($id);
    }

    public function update($id, Request $request){
        // if(Auth::user()->rol_id != 1 && Auth::user()->id != $id){
            // return custom_response(false,'Desautorizado',[],401);
        // }
        return parent::_update($id,$request);
    }

    public function destroy($id, Request $request){
        // if(Auth::user()->rol_id != 1 && Auth::user()->id != $id){
        //     return custom_response(false,'Desautorizado',[],401);
        // }
        return parent::_destroy($id,$request);
    }
}

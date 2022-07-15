<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\User\UserService;

/** @property UserService $service */
class UserController extends CrudController
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request){
        return parent::_index($request);
    }
}

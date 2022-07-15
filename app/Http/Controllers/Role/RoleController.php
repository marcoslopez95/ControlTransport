<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Role\RoleService;
/** @property RoleService $service */
class RoleController extends CrudController
{
    public function __construct(RoleService $service)
    {
        parent::__construct($service);
    }
}
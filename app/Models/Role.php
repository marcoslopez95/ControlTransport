<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Role extends CrudModel
{
    protected $guarded = ['id'];
}
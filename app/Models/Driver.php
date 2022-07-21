<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Driver extends CrudModel
{
    protected $fillable = [
        'first_name',
        'last_name',
        'ci'
    ];
}

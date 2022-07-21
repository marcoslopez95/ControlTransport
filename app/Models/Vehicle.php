<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Vehicle extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'plate',
        'num_control',
        'description',
        'status',
    ];
}

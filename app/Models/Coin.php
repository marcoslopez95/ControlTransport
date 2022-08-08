<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Coin extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'symbol'
    ];
}

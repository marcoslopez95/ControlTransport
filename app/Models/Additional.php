<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Additional extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'description',
        'percent',
        'coin_id',
        'type',
    ];
}

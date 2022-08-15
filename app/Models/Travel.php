<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Travel extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'date_start',
        'date_end',
        'vehicle_id',
        'observation',
    ];

    public function amountable(){
        return $this->morphMany(Amount::class,'amountable');
    }
}

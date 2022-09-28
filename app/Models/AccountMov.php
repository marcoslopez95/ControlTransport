<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountMov extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'date',
        'description',
        'type',
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

    public function amountMovs(){
        return $this->hasMany(AmountMov::class);
    }
}

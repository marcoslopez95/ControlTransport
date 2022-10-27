<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'liquidation_id',
        'document',
        'first_name',
        'last_name',
        'coin_id',
        'ammount'
    ];

    /**
     * get coin of the passengers
     */
    public function coin(){
        return $this->belongsTo(Coin::class);
    }

    /**
     * get liquidation of the passengers
     */
    public function liquidation(){
        return $this->belongsTo(Liquidation::class);
    }
}

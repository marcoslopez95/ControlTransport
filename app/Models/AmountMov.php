<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmountMov extends Model
{
    use HasFactory;
    protected $fillable = [
        'coin_id',
        'quantity',
        'account_mov_id'
    ];

    public function accountMov(){
        return $this->belongsTo(AccountMov::class);
    }

    public function coin(){
        return $this->belongsTo(Coin::class);
    }
}

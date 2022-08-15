<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    use HasFactory;

    protected $hidden = ['created_at','updated_at'];

    protected $fillable =[
            'coin_id',
            'quantity',
            // 'liquidation_id',
            'neto',
            'received',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'coin_id'        => 'integer',
        // 'liquidation_id' => 'integer',
        'quantity'       => 'float',
        'neto'           => 'float',
        'received'       => 'float',
    ];

    /**
     * Get the coin that owns the Amount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }
    /**
     * Get the liquidation that owns the Ammount
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function liquidation()
    // {
    //     return $this->belongsTo(Liquidation::class);
    // }

    public function amountable(){
        return $this->morphTo();
    }
}

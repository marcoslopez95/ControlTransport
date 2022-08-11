<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Liquidation extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'vehicle_id',
        'precio_pasaje',
        'coin_id',
        'date',
        'pasajeros',
    ];

    /**
     * Get the vehicles that owns the Liquidation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vehicles()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the coin that owns the Liquidation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }
}

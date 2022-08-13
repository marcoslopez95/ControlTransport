<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Liquidation extends CrudModel
{
    protected $guarded = ['id','total','falta'];
    protected $fillable = [
        'vehicle_id',
        'precio_pasaje',
        'coin_id',
        'date',
        'pasajeros',
        'office_origin',
        'office_destiny'
    ];

    /**
     * Get the officeOrigin that owns the Liquidation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function officeOrigin()
    {
        return $this->belongsTo(Office::class, 'office_origin');
    }

    /**
     * Get the officeDestiny that owns the Liquidation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function officeDestiny()
    {
        return $this->belongsTo(Office::class, 'office_destiny');
    }

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

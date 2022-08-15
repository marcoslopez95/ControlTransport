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
        'total',
        'falta',
        'pasajeros',
        'office_origin',
        'office_destiny'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'             => 'integer',
        'coin_id'        => 'integer',
        'vehicle_id'     => 'integer',
        'office_origin'  => 'integer',
        'office_destiny' => 'integer',
        'precio_pasaje'  => 'float',
        'total'          => 'float',
        'falta'          => 'float',
    ];

    /**
     * Get all of the ammounts for the Liquidation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function ammounts()
    // {
    //     return $this->hasMany(Amount::class);
    // }

    /**
     * The additionals that belong to the Liquidation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function additionals()
    {

        return $this->belongsToMany(Additional::class);
    }

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
    public function vehicle()
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

    public function ammounts(){
        return $this->morphMany(Amount::class,'amountable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Builder;

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

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get all of the liquidations for the Travel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function liquidations()
    {
        return $this->hasMany(Liquidation::class);
    }

    public function scopeFilter(Builder $builder, $request){
        return $builder
            ->when($request->vehicle_id,function(Builder $q,$vehicleId){
                return $q->where('vehicle_id',$vehicleId);
            });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Travel extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'date_start',
        'date_end',
        'vehicle_id',
        'observation',
    ];

    // public function amountable(){
    //     return $this->morphMany(Amount::class,'amountable');
    // }

    /**
     * Get all of the montos for the Travel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function montos()
    {
        return $this->hasManyThrough(Amount::class, Liquidation::class,'travel_id','amountable_id');
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
            ->when($request->travel_id,function(Builder $query,$travel_id){
                return $query->where('id',$travel_id);
            })
            ->when($request->vehicle_id,function(Builder $q,$vehicleId){
                return $q->where('vehicle_id',$vehicleId);
            })
            ->when(($request->date_start && $request->date_end),function(Builder $query) use ($request){
                $start = $request->date_start;
                $end   = $request->date_end;

                return $query->whereBetween('date_start',[$start,$end])
                    ->orWhereBetween('date_end',[$start,$end]);
            })
            ;
    }

    public function scopeSocio(Builder $builder){
        return $builder->whereHas('vehicle',function(Builder $query){
            $user = Auth::user();
            return $query->where('partner_id',$user->partner_id);
        });
    }

    /**
     * Get all of the gastos for the Travel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gastos()
    {
        return $this->belongsToMany(Gasto::class,'gasto_triver_reception','triver_reception_id');
    }
}

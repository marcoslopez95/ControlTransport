<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
        'office_destiny',
        'type_travel',
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

    public function scopeFilter(Builder $builder, $request){
        return $builder
            ->when($request->vehicle_id,function(Builder $q,$vehicle_id){
                return $q->where('vehicle_id',$vehicle_id);
            })
            ->when($request->office_origin,function(Builder $q,$office_origin){
                return $q->where('office_origin',$office_origin);
            })
            ->when($request->office_destiny,function(Builder $q,$office_destiny){
                return $q->where('office_destiny',$office_destiny);
            })
            ->when($request->travel_id,function(Builder $q,$travel_id){
                return $q->where('travel_id',$travel_id);
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

    public function viaje(){
        return $this->belongsTo(Travel::class);
    }
}

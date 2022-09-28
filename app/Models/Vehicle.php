<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Vehicle extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'plate',
        'num_control',
        'description',
        'status',
        'partner_id'
    ];

    /**
     * Get the socio that owns the Vehicle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
    public function scopeFilter(Builder $builder, $request){
        return $builder
            ->when($request->value,function(Builder $q, $value){
                return $q->where(function(Builder $q) use ($value){
                    return $q
                        ->where('plate',$value);
                });
            })
            ->when($request->socio, function(Builder $query,$socio){
                return $query->where('partner_id',$socio);
            })
            ;
    }

    public function scopeSocio(Builder $builder){
        $user = Auth::user();
        return $builder->where('partner_id',$user->partner_id);
    }

    public function travels(){
        return $this->hasMany(Travel::class);
    }

    public function accountMovs(){
        return $this->hasMany(AccountMov::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Builder;

class Office extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'description',
        'type',// 'Priv.','Pub.'
    ];

    public function scopeFilter(Builder $builder, $request){
        return $builder
            ->when($request->name,function(Builder $q,$name){
                return $q->where('name','ilike',"%$name%");
            });
    }

    /**
     * Get all of the officeDestino for the Office
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function officeOrigen()
    {
        return $this->hasMany(Liquidation::class,'office_origin');
    }

    public function officeDestino()
    {
        return $this->hasMany(Liquidation::class,'office_destiny');
    }
}

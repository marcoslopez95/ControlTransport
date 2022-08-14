<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Builder;

class Additional extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'description',
        'percent',
        'quantity',
        'coin_id',
        'type', // Descuento, Retencion
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'coin_id' => 'integer',
        'percent' => 'float',
        'quantity' => 'float',
    ];

    /**
     * Get the coin that owns the Additional
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }
    public function scopeFilter(Builder $query, $request){
        return $query->when($request->description,function(Builder $q,$description){
            return $q->where('description','ilike',"%$description%");
        })
        ->when($request->type,function(Builder $q,$type){
            return $q->where('type',$type);
        })
        ;
    }
}

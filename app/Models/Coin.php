<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Builder;

//use Illuminate\Contracts\Database\Eloquent\Builder;

class Coin extends CrudModel
{
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'symbol'
    ];

    public function scopeFilter(Builder $query, $request){
        return $query->when($request->name,function(Builder $query,$name){
            return $query->where('name',$name);
        });
    }

    /**
     * Get all of the additionals for the Coin
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function additionals()
    {
        return $this->hasMany(Additional::class);
    }
    public function ammounts()
    {
        return $this->hasMany(Amount::class);
    }
    public function liquidation()
    {
        return $this->hasOne(Liquidation::class);
    }
}

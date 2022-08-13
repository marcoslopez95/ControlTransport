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
}

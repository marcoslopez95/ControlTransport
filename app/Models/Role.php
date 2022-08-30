<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Builder;

class Role extends CrudModel
{
    protected $guarded = ['id'];

    public function scopeFilter(Builder $query, $request){
        return $query->when($request->name,function(Builder $query,$name){
            return $query->where('name','ilike',"%$name%");
        });
    }
}

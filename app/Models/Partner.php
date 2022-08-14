<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'ci'
    ];

    public function scopeFilter(Builder $builder, $request){
        return $builder
            ->when($request->value,function(Builder $q,$value){
                return $q->where(function(Builder $q) use ($value){
                    return $q
                        ->where('first_name','ilike',"%$value%")
                        ->orWhere('last_name','ilike',"%$value%")
                        ->orWhere('ci','ilike',"%$value%");
                });
            });
    }
}

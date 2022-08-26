<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Builder;

class Gasto extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'gastos';
    protected $fillable = [
        'description',
        'quantity',
        'coin_id'
    ];

    public function scopeFilter(Builder $builder,$request){
        return $builder
            ->when($request->description,function (Builder $query, $description){
                return $query->where('description','ilike',"%$description%");
            });
    }
}

<?php

namespace App\Traits;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Builder;

trait TravelTrait {
    protected function verificarGastosYTotal(Travel $travel){
        $sumatoria_liquidaciones = $travel->liquidations->sum('total');

        $gastos = $travel->gastos()->whereHas('coin',function(Builder $query){
            return $query->where('symbol','USD');
        })->get();

        $sumatoria_gastos = $gastos->sum('quantity');

        return $sumatoria_liquidaciones >= $sumatoria_gastos;
    }
}

<?php

/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Travel;


use App\Core\CrudService;
use App\Models\Coin;
use App\Models\Liquidation;
use App\Repositories\Travel\TravelRepository;
use Illuminate\Http\Request;

/** @property TravelRepository $repository */
class TravelService extends CrudService
{

    protected $name = "travel";
    protected $namePlural = "travel";

    public function __construct(TravelRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _index(?Request $request = null)
    {
        $travels = $this->repository->_index($request);

        $travels->load(['vehicle', 'montos', 'gastos']);

        foreach ($travels as $travel) {
            $sumatorias      = self::SumTotalLiquidations($travel->liquidations);
            $total_gastos    = self::TotalGastos($travel->gastos);
            $travel['neto']  = $sumatorias['total'];
            $travel['debe']  = $sumatorias['debe'];
            $m = [];
            $travel['total'] = $sumatorias['total'] - $total_gastos;
            foreach ($travel->montos as $monto) {
                $monto['coin_name'] = (Coin::find($monto->coin_id))->name;
                if (!array_key_exists($monto['coin_name'], $m)) {
                    $m[$monto['coin_name']]['total'] = 0;
                    $m[$monto['coin_name']]['recibido'] = 0;
                }
                $m[$monto['coin_name']]['total'] += $monto->quantity;
            }
            unset($travel['montos']);
            $travel['montos'] = $m;
        }

        return $travels;
    }

    private function TotalGastos($gastos)
    {
        $sum = 0;
        if (count($gastos) > 0) {
            foreach ($gastos as $gasto) {
                $sum += $gasto->quantity;
            }
        }
        return $sum;
    }
    private function SumTotalLiquidations($liquidations)
    {
        $total = $debe = 0;
        if (count($liquidations) > 0) {
            foreach ($liquidations as $liquidation) {
                $total += $liquidation->total;
                $debe  += $liquidation->falta;
            }
        }

        return [
            'total' => $total,
            'debe'  => $debe
        ];
    }

    public function _show($id, $request = null)
    {
        $travel = $this->repository->_show($id);
        $travel->load(['vehicle', 'montos', 'gastos']);

        $sumatorias = self::SumTotalLiquidations($travel->liquidations);
        $total_gastos    = self::TotalGastos($travel->gastos);
        $travel['total'] = $sumatorias['total'];
        $travel['debe'] = $sumatorias['debe'];
        $m = [];
        $travel['total'] = $sumatorias['total'] - $total_gastos;
        foreach ($travel->montos as $monto) {
            $monto['coin_name'] = (Coin::find($monto->coin_id))->name;
            if (!array_key_exists($monto['coin_name'], $m)) {
                $m[$monto['coin_name']]['total'] = 0;
                $m[$monto['coin_name']]['recibido'] = 0;
            }
            $m[$monto['coin_name']]['total'] += $monto->quantity;

        }
        unset($travel['montos']);
        $travel['montos'] = $m;
        return $travel;
    }

    public function _update($id, Request $request)
    {
        $travel = $this->repository->_show($id);

        return $this->repository->_update($id, $request->only('observation'));
    }
}

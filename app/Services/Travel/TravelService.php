<?php

/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Travel;


use App\Core\CrudService;
use App\Models\Coin;
use App\Models\Liquidation;
use App\Models\Office;
use App\Repositories\Travel\TravelRepository;
use App\Traits\TravelTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** @property TravelRepository $repository */
class TravelService extends CrudService
{

    use TravelTrait;

    protected $name = "travel";
    protected $namePlural = "travel";

    public function __construct(TravelRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _index(?Request $request = null)
    {
        $travels = $this->repository->_index($request);

        $coins = Coin::all();
        $travels->load([
            'vehicle',
            'montos',
            'gastos.coin',
            'drivers',
            'liquidations.additionals'
        ]);

        foreach ($travels as $travel) {
            $sumatorias      = self::SumTotalLiquidations($travel->liquidations);
            $total_gastos    = self::TotalGastos($travel->gastos);
            $travel['neto']  = $sumatorias['total'];
            $travel['debe']  = $sumatorias['debe'];
            $m = [];
            $travel['total'] = $sumatorias['total'] - $total_gastos;
            $travel->liquidations->transform(function($item,$value) use ($coins){
                $coin = $coins->find($item->coin_id);
                $item['coin_name'] = $coin->name;
                $oficina_origen = Office::find($item->office_origin);
                $oficina_destino = Office::find($item->office_destiny);
                $item['name_office_origin'] = $oficina_origen->name;
                $item['name_office_destiny'] = $oficina_destino->name;
                return $item;
            });
            foreach ($travel->montos as $monto) {
                $monto['coin_name'] = (Coin::find($monto->coin_id))->name;
                if (!array_key_exists($monto['coin_name'], $m)) {
                    $m[$monto['coin_name']]['total'] = 0;
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
        $travel->load(['vehicle', 'montos', 'gastos.coin','drivers',]);

        $coins = Coin::all();

        $sumatorias = self::SumTotalLiquidations($travel->liquidations);
        $total_gastos    = self::TotalGastos($travel->gastos);
        $travel['total'] = $sumatorias['total'];
        $travel['debe'] = $sumatorias['debe'];
        $m = [];
        $travel['total'] = $sumatorias['total'] - $total_gastos;

        $travel->liquidations->transform(function($item,$value) use ($coins){
            $coin = $coins->find($item->coin_id);
            $item['coin_name'] = $coin->name;
            $oficina_origen = Office::find($item->office_origin);
            $oficina_destino = Office::find($item->office_destiny);
            $item['name_office_origin'] = $oficina_origen->name;
            $item['name_office_destiny'] = $oficina_destino->name;
            return $item;
        });

        foreach ($travel->montos as $monto) {
            $monto['coin_name'] = (Coin::find($monto->coin_id))->name;
            if (!array_key_exists($monto['coin_name'], $m)) {
                $m[$monto['coin_name']]['total'] = 0;
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
        if(!$travel->open){
            throw new \Exception('¡No se puede editar un viaje cerrado!');
        }

        if(!self::verificarGastosYTotal($travel)){
            throw new \Exception('¡Los gastos superan el monto total del viaje!');
        }

        $travel = $this->repository->_update($id, $request->only('observation','open'));

        $travel->gastos()->sync($request->gastos);

        $travel->drivers()->sync($request->drivers);

        return $travel;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Travel;


use App\Core\CrudService;
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

        $travels->load('vehicle');

        foreach($travels as $travel){
            $sumatorias = self::SumTotalLiquidations($travel->liquidations);
            $travel['total'] = $sumatorias['total'];
            $travel['debe'] = $sumatorias['debe'];

        }

        return $travels;
    }

    private function SumTotalLiquidations($liquidations){
        $total = $debe = 0;
        if(count($liquidations) > 0){
            foreach($liquidations as $liquidation){
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
        $travel->load(['vehicle','liquidations']);
        $sumatorias = self::SumTotalLiquidations($travel->liquidations);
        $travel['total'] = $sumatorias['total'];
        $travel['debe'] = $sumatorias['debe'];
        return $travel;
    }

    public function _update($id, Request $request)
    {
        return $this->repository->_update($id,$request->only('observation'));
    }

}

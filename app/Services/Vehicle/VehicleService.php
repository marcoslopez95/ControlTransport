<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Vehicle;


use App\Core\CrudService;
use App\Models\Coin;
use App\Models\Vehicle;
use App\Repositories\Vehicle\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** @property VehicleRepository $repository */
class VehicleService extends CrudService
{

    protected $name = "vehicle";
    protected $namePlural = "vehicles";

    public function __construct(VehicleRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _index(?Request $request = null)
    {
        $vehicles = $this->repository->_index($request);
        $vehicles->load('partner');
        return $vehicles;
    }

    public function _show($id, $request = null)
    {
        $vehicle = parent::_show($id);
        $vehicle->load('partner');
        return $vehicle;
    }

    public function cajaChicaByVehicle(Vehicle $vehicle){
        $coin = Coin::all();

        $montos = DB::table('amounts')
            ->select([
                'amounts.coin_id',
                'coins.name as coin_name',
                DB::raw('sum("quantity")'),
            ])
            ->join('liquidations','liquidations.id','=','amounts.amountable_id')
            ->join('travel','travel.id','=','liquidations.travel_id')
            ->join('coins','amounts.coin_id','=','coins.id')
            ->where('amounts.amountable_type','like','%Liquidation%')
            ->where('liquidations.vehicle_id','=',$vehicle->id)
            ->groupBy([
                'amounts.coin_id','coin_name'
            ])
            ->get()
            ;

        $gastos = DB::table('gastos')
                ->select([
                    'gastos.coin_id',
                    'coins.name as coin_name',
                    DB::raw('sum("quantity")'),
                ])
                ->join('gasto_triver_reception','gastos.id','=','gasto_id')
                ->join('travel','travel.id','=','triver_reception_id')
                ->join('coins','gastos.coin_id','=','coins.id')
                ->where('travel.vehicle_id','=',$vehicle->id)
                ->groupBy([
                    'gastos.coin_id','coin_name'
                ])
                ->get()
                ;

        foreach($gastos as $gasto){
            $bool = $montos->where('coin_id',$gasto->coin_id)->first();

            if($bool){
                $bool->sum -= $gasto->sum;
            }
        }

        return [
            'vehicle_id'    => $vehicle->id,
            'vehicle'       => $vehicle,
            'count_travels' => $vehicle->travels->count(),
            'caja-chica'    => $montos
        ];
    }
}

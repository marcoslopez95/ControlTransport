<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Liquidation;


use App\Core\CrudService;
use App\Events\NewLiquidationRegisteredEvent;
use App\Models\Additional;
use App\Models\Coin;
use App\Models\Office;
use App\Models\Travel;
use App\Repositories\Additional\AdditionalRepository;
use App\Repositories\Coin\CoinRepository;
use App\Repositories\Liquidation\LiquidationRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/** @property LiquidationRepository $repository */
class LiquidationService extends CrudService
{

    protected $name = "liquidation";
    protected $namePlural = "liquidations";
    protected $coin_repo;


    public function __construct(LiquidationRepository $repository)
    {
        parent::__construct($repository);
        $this->coin_repo = new CoinRepository(new Coin());

    }

    public function _store(Request $request)
    {
        if($request->type_travel == 'Llegada'){
            $travel = Travel::where('vehicle_id',$request->vehicle_id)
                ->where('status','En Viaje')
                ->first();
            if(!$travel){
                throw new Exception('El vehiculo no tiene viajes iniciados');
            }
        }

        $total_liquidation = $request->pasajeros * $request->precio_pasaje;
        $default_coin      = $this->coin_repo->defaultCoin();

        $neto = self::calculateAdditionals($total_liquidation, $request->additionals);
        $request['total'] = $neto;

        $data_liquidation = $request->except(['ammounts','additionals']);

        $liquidation   = $this->repository->_store($data_liquidation);
        $request['id'] = $liquidation->id;
        $this->repository->saveAdditionals($liquidation->id,$request->only('additionals'));
        $this->repository->saveAmount($liquidation->id,$request->only('ammounts'));
        event(new NewLiquidationRegisteredEvent($liquidation,$request->type_travel));
        return $request->all();

    }

    private function calculateAdditionals($base, $additionals){
        Log::alert("calculateAdditionals: --base-- $base ..... ids_add: ".implode(',',$additionals));

        if(!$additionals){
            return 0;
        }

        $additionals = Additional::whereIn('id',$additionals)->get();

        if($additionals->count() == 0){
            return 0;
        }

        $acum = 0;
        foreach($additionals as $additional){
            $desc_ammount = 0;
            if(!$additional->quantity && $additional->percent > 0){
                Log::alert('adicionales: ' . $additional->percent);
                $desc_ammount = ($additional->percent / 100);
                $acum += $base * $desc_ammount;
            }else
            if($additional->quantity > 0 && !$additional->percent){
                Log::alert('adicionales: ' . $additional->quantity);

                $acum += $additional->quantity;
            }
            Log::alert('acum: ' . $acum);

        }

        if($acum > $base){
            throw new \Exception('¡Los impuestos son superiores al total de la liquidación!');
        }

        return $base - $acum;
    }

    public function _index(?Request $request = null)
    {
        $liquidations = $this->repository->_index($request);

        $liquidations->load(['additionals','ammounts.coin','vehicle','coin']);

        $liquidations->transform(function($item,$value){
            $oficina_origen = Office::find($item->office_origin);
            $oficina_destino = Office::find($item->office_destiny);
            $item['name_office_origin'] = $oficina_origen->name;
            $item['name_office_destiny'] = $oficina_destino->name;
            return $item;
        });
        return  $liquidations;
    }


    public function _show($id, $request = null)
    {
        $liquidation = parent::_show($id);

        $oficina_origen = Office::find($liquidation->office_origin);
        $oficina_destino = Office::find($liquidation->office_destiny);
        $liquidation['name_office_origin'] = $oficina_origen->name;
        $liquidation['name_office_destiny'] = $oficina_destino->name;

        $liquidation->load(['additionals','ammounts.coin','vehicle','coin']);

        foreach($liquidation->additionals as $additional){
            unset($additional['pivot']);
        }
        return $liquidation;
    }

    public function _update($id, Request $request)
    {

        $total_liquidation = $request->pasajeros * $request->precio_pasaje;
        $default_coin      = $this->coin_repo->defaultCoin();

        $neto = self::calculateAdditionals($total_liquidation, $request->additionals);
        $request['total'] = $neto;

        $data_liquidation = $request->except(['ammounts','additionals']);

        $liquidation   = $this->repository->_update($id,$data_liquidation);
        $request['id'] = $id;
        $this->repository->syncAdditionals($liquidation->id,$request->only('additionals'));
        $this->repository->updateAmount($liquidation->id,$request->only('ammounts'));

        return self::_show($id);

    }
}

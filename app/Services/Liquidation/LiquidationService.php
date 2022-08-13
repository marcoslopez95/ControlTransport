<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Liquidation;


use App\Core\CrudService;
use App\Models\Additional;
use App\Models\Coin;
use App\Repositories\Additional\AdditionalRepository;
use App\Repositories\Coin\CoinRepository;
use App\Repositories\Liquidation\LiquidationRepository;
use Illuminate\Http\Request;

/** @property LiquidationRepository $repository */
class LiquidationService extends CrudService
{

    protected $name = "liquidation";
    protected $namePlural = "liquidations";
    protected $coin_repo;
    

    public function __construct(LiquidationRepository $repository)
    {
        parent::__construct($repository);
        $coin_repo = new CoinRepository(new Coin());
        
    }

    public function _store(Request $request)
    {
        
        $total_liquidation = $request->pasajeros * $request->precio_pasaje;
        $default_coin = $this->coin_repo->defaultCoin();

        $neto = self::calculateAdditionals($total_liquidation, $request->additionals);

        $data_liquidation = $request->except(['ammounts','additionals']);

        $liquidation = $this->repository->_store($data_liquidation);

    }

    private function calculateAdditionals($base, $additionals){
        $additionals = Additional::where('id',$additionals)->get();
        
    }

}
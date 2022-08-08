<?php

namespace App\Http\Controllers\Coin;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Http\Requests\CoinRequest;
use App\Services\Coin\CoinService;
/** @property CoinService $service */
class CoinController extends CrudController
{
    public function __construct(CoinService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request){
        return parent::_index($request);
    }

    public function store(CoinRequest $request){
        return parent::_store($request);
    }

    public function show($coin,Request $request){
        return parent::_show($coin);
    }

    public function update($coin, CoinRequest $request){
        return parent::_update($coin,$request);
    }

    public function destroy($coin,Request $request){
        return parent::_destroy($coin,$request);
    }
}

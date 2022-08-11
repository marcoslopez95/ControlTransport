<?php

namespace App\Http\Controllers\Liquidation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Liquidation\LiquidationService;
/** @property LiquidationService $service */
class LiquidationController extends CrudController
{
    public function __construct(LiquidationService $service)
    {
        parent::__construct($service);
    }

    public function store(Request $request){
        return parent::_store($request);
    }
}

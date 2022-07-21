<?php

namespace App\Http\Controllers\Vehicle;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Http\Requests\VehicleRequest;
use App\Services\Vehicle\VehicleService;
/** @property VehicleService $service */
class VehicleController extends CrudController
{
    public function __construct(VehicleService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request){
        return parent::_index($request);
    }

    public function store(VehicleRequest $request){
        return parent::_store($request);
    }

    public function show($id, Request $request){
        return parent::_show($id);
    }

    public function update($id, VehicleRequest $request)
    {
        return parent::_update($id,$request);
    }

    public function destroy($id, Request $request){
        return parent::_destroy($id, $request);
    }
}

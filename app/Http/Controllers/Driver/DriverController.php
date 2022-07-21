<?php

namespace App\Http\Controllers\Driver;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Http\Requests\DriverRequest;
use App\Services\Driver\DriverService;
/** @property DriverService $service */
class DriverController extends CrudController
{
    public function __construct(DriverService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request){
        return parent::_index($request);
    }

    public function store(DriverRequest $request){
        return parent::_store($request);
    }

    public function show($id, Request $request){
        return parent::_show($id);
    }

    public function update($id, DriverRequest $request)
    {
        return parent::_update($id,$request);
    }

    public function destroy($id, Request $request){
        return parent::_destroy($id, $request);
    }
}

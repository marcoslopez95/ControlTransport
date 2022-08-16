<?php

namespace App\Http\Controllers\Travel;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Travel\TravelService;
/** @property TravelService $service */
class TravelController extends CrudController
{
    public function __construct(TravelService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request){
        return parent::_index($request);
    }

    public function show($id, Request $request){
        return parent::_show($id);
    }

    public function update($id, Request $request){
        return parent::_update($id, $request);
    }

    public function destroy($id, Request $request)
    {
        return parent::_destroy($id, $request);
    }
}

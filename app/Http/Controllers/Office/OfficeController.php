<?php

namespace App\Http\Controllers\Office;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Office\OfficeService;
/** @property OfficeService $service */
class OfficeController extends CrudController
{
    public function __construct(OfficeService $service)
    {
        parent::__construct($service);
    }

    public function index(Request $request){
        return parent::_index($request);
    }

    public function store(Request $request){
        return parent::_store($request);
    }

    public function show($id, Request $request){
        return parent::_show($id);
    }

    public function update($id, Request $request){
        return parent::_update($id,$request);
    }

    public function destroy($id, Request $request){
        return parent::_destroy($id,$request);
    }
}

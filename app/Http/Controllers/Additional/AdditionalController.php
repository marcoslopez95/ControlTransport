<?php

namespace App\Http\Controllers\Additional;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Http\Requests\AdditionalRequest;
use App\Services\Additional\AdditionalService;
/** @property AdditionalService $service */
class AdditionalController extends CrudController
{
    public function __construct(AdditionalService $service)
    {
        parent::__construct($service);
    }

    public function store(AdditionalRequest $request){
        return parent::_store($request);
    }

    public function index(Request $request){
        return parent::_index($request);
    }

    public function show($additional){
        return parent::_show($additional);
    }

    public function update($additional, AdditionalRequest $request){
        return parent::_update($additional,$request);
    }

    public function destroy($additional, Request $request){
        return parent::_destroy($additional,$request);
    }
}

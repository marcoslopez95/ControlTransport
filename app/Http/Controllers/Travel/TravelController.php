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
}
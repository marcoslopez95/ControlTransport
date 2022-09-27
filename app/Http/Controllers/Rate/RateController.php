<?php

namespace App\Http\Controllers\Rate;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Rate\RateService;
/** @property RateService $service */
class RateController extends CrudController
{
    public function __construct(RateService $service)
    {
        parent::__construct($service);
    }
}
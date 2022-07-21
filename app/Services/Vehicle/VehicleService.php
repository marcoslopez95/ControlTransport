<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Vehicle;


use App\Core\CrudService;
use App\Repositories\Vehicle\VehicleRepository;

/** @property VehicleRepository $repository */
class VehicleService extends CrudService
{

    protected $name = "vehicle";
    protected $namePlural = "vehicles";

    public function __construct(VehicleRepository $repository)
    {
        parent::__construct($repository);
    }

}
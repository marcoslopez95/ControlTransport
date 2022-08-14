<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Vehicle;


use App\Core\CrudService;
use App\Repositories\Vehicle\VehicleRepository;
use Illuminate\Http\Request;

/** @property VehicleRepository $repository */
class VehicleService extends CrudService
{

    protected $name = "vehicle";
    protected $namePlural = "vehicles";

    public function __construct(VehicleRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _index(?Request $request = null)
    {
        $vehicles = $this->repository->_index($request);
        $vehicles->load('partner');
        return $vehicles;
    }

    public function _show($id, $request = null)
    {
        $vehicle = parent::_show($id);
        $vehicle->load('partner');
        return $vehicle;
    }
}

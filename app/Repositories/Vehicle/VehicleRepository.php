<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Vehicle;

use App\Core\CrudRepository;
use App\Models\Vehicle;

/** @property Vehicle $model */
class VehicleRepository extends CrudRepository
{

    public function __construct(Vehicle $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $vehicles = $this->model::filter($request)->orderBy('id','desc')->get();
        return $vehicles;
    }

}

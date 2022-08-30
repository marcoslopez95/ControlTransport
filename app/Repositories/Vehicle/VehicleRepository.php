<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Vehicle;

use App\Core\CrudRepository;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

/** @property Vehicle $model */
class VehicleRepository extends CrudRepository
{

    public function __construct(Vehicle $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        if(Auth::user()->role_id == 1){
            $vehicles = $this->model::filter($request)->orderBy('id','desc')->get();
        }else{
            $vehicles = $this->model::filter($request)->socio()->orderBy('id','desc')->get();

        }
        return $vehicles;
    }

}

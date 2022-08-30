<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Travel;

use App\Core\CrudRepository;
use App\Models\Travel;
use Illuminate\Support\Facades\Auth;

/** @property Travel $model */
class TravelRepository extends CrudRepository
{

    public function __construct(Travel $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        if(Auth::user()->role_id == 1){
            $travels = $this->model::filter($request)->orderBy('id','desc')->get();
        }else{
            $travels = $this->model::filter($request)->socio()->orderBy('id','desc')->get();

        }

        return $travels;
    }

}

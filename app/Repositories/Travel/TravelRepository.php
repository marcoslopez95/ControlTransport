<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Travel;

use App\Core\CrudRepository;
use App\Models\Travel;

/** @property Travel $model */
class TravelRepository extends CrudRepository
{

    public function __construct(Travel $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $travels = $this->model::filter($request)->orderBy('id','desc')->get();

        return $travels;
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Office;

use App\Core\CrudRepository;
use App\Models\Office;

/** @property Office $model */
class OfficeRepository extends CrudRepository
{

    public function __construct(Office $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $offices = $this->model::filter($request)->orderBy('id','desc')->get();

        return $offices;
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Gastos;

use App\Core\CrudRepository;
use App\Models\Gasto;

/** @property Gasto $model */
class GastosRepository extends CrudRepository
{

    public function __construct(Gasto $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $gastos = $this->model::filter($request)->get();

        return $gastos;
    }

}

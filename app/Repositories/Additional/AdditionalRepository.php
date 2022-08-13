<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Additional;

use App\Core\CrudRepository;
use App\Models\Additional;

/** @property Additional $model */
class AdditionalRepository extends CrudRepository
{

    public function __construct(Additional $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null): Additional
    {
        $additionals = $this->model::Filter($request)->get();

        return $additionals;
    }

}
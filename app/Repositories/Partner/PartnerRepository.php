<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Partner;

use App\Core\CrudRepository;
use App\Models\Partner;

/** @property Role $model */
class PartnerRepository extends CrudRepository
{

    public function __construct(Partner $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        $socios = $this->model::filter($request)->orderBy('id','desc')->get();

        return $socios;
    }

}

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

}

<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Rate;

use App\Core\CrudRepository;
use App\Models\Rate;

/** @property Rate $model */
class RateRepository extends CrudRepository
{

    public function __construct(Rate $model)
    {
        parent::__construct($model);
    }

}
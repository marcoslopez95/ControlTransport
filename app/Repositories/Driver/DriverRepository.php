<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Driver;

use App\Core\CrudRepository;
use App\Models\Driver;

/** @property Driver $model */
class DriverRepository extends CrudRepository
{

    public function __construct(Driver $model)
    {
        parent::__construct($model);
    }

}
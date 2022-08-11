<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Liquidation;

use App\Core\CrudRepository;
use App\Models\Liquidation;

/** @property Liquidation $model */
class LiquidationRepository extends CrudRepository
{

    public function __construct(Liquidation $model)
    {
        parent::__construct($model);
    }

}
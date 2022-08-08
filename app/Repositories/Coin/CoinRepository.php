<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Coin;

use App\Core\CrudRepository;
use App\Models\Coin;

/** @property Coin $model */
class CoinRepository extends CrudRepository
{

    public function __construct(Coin $model)
    {
        parent::__construct($model);
    }

}
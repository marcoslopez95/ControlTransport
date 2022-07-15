<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\User;

use App\Core\CrudRepository;
use App\Models\User;

/** @property User $model */
class UserRepository extends CrudRepository
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

}

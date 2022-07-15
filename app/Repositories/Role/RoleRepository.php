<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Role;

use App\Core\CrudRepository;
use App\Models\Role;

/** @property Role $model */
class RoleRepository extends CrudRepository
{

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

}
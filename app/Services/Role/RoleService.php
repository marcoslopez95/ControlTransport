<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Role;


use App\Core\CrudService;
use App\Repositories\Role\RoleRepository;

/** @property RoleRepository $repository */
class RoleService extends CrudService
{

    protected $name = "role";
    protected $namePlural = "roles";

    public function __construct(RoleRepository $repository)
    {
        parent::__construct($repository);
    }

}
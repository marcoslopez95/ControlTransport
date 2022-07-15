<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\User;


use App\Core\CrudService;
use App\Repositories\User\UserRepository;

/** @property RoleRepository $repository */
class UserService extends CrudService
{

    protected $name = "user";
    protected $namePlural = "users";

    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

}

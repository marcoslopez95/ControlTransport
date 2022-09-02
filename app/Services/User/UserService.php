<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\User;


use App\Core\CrudService;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

/** @property RoleRepository $repository */
class UserService extends CrudService
{

    protected $name = "user";
    protected $namePlural = "users";

    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _index(?Request $request = null)
    {
        $users = $this->repository->_index($request);
        $users->load('socio');

        return $users;
    }

    public function _show($id, $request = null)
    {
        $user = $this->repository->_show($id);
        $user->load('socio');
        return $user;
    }

}

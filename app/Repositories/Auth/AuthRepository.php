<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Auth;

use App\Core\CrudRepository;
use App\Models\Role;
use App\Models\User;

/** @property Auth $model */
class AuthRepository extends CrudRepository
{

    protected $role_id;
    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->role_id = (Role::firstWhere('name','User'))->id;
    }

    /**
     * Registra un usuario
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return $this->model::create([...$data,'role_id'=>$this->role_id]);
    }

    public function listUsers($request){

        $users = $this->model::paginate($request->paginate ?? 15);
        return $users;
    }

    public function login($email){
        return $this->model::firstWhere('email',$email);
    }
}

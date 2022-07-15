<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Auth;


use App\Core\CrudService;
use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/** @property AuthRepository $repository */
class AuthService extends CrudService
{

    protected $name = "auth";
    protected $namePlural = "auths";
    protected $fields = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    public function __construct(AuthRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Pasa los datos necesarios para registrar un usuario
     *
     * @param Request $data
     * @return User
     */
    public function register(Request $data): User
    {
        return $this->repository->register($data->only($this->fields));
    }

    public function listUsers($request){
        return $this->repository->listUsers($request);
    }

    public function login(Request $request){
        $user = $this->repository->login($request->email);

        if(!Hash::check($request->password,$user->password) ){
            throw(new Exception('Contraseña incorrecta'));
        }
        $json = [
            'token' => $user->createToken('login')->plainTextToken,
            'name'  => $user->first_name,
            'role'  => 1
        ];
        return $json;
    }
}

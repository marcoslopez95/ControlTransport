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
        'role_id',
        'partner_id'
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

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw(new Exception('Contraseña incorrecta'));
        }
        $token = $user->createToken('login')->plainTextToken;
        $pos = strpos($token,'|');
        $token = substr($token,$pos+1);
        $json = [
            'token' => $token,
            'name'  => $user->first_name,
            'role'  => $user->role_id
        ];
        return $json;
    }
}

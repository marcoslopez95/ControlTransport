<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/** @property AuthService $service */
class AuthController extends CrudController
{
    public function __construct(AuthService $service)
    {
        parent::__construct($service);
    }

    /**
     * Valida el request para hacer el registro
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try{
            $data = $this->service->register($request);
            DB::commit();
            return custom_response(true,'Registro Exitoso',$data);
        }catch(\Exception $e){
            DB::rollBack();
            $mensaje = 'No se pudo crear el usuario';
            $data = $e->getMessage();
            return custom_error($e,$mensaje,$data);
        }
    }

    public function listUsers(Request $request){
        try{
            $data = $this->service->listUsers($request);
            return custom_response(true,'Lista de usuarios',$data);
        }catch(\Exception $e){
            DB::rollBack();
            $mensaje = 'Error';
            $data = $e->getMessage();
            return custom_error($e,$mensaje,$data);
        }
    }

    public function login(LoginRequest $request){
        try{
            $data = $this->service->login($request);
            return custom_response(true,'Inicio de sesión',$data);
        }catch(\Exception $e){
            $mensaje = 'Error al iniciar sesión';
            $data = $e->getMessage();
            return custom_error($e,$mensaje,$data,425);
        }
    }

    public function logout(Request $request){
        try{
            $user = Auth::user();
                $user->tokens()->delete();
            return custom_response(true,'logout exitoso');
        }catch(\Exception $e){
            $mensaje = 'Error al cerrar sesión';
            $data = $e->getMessage();
            return custom_error($e,$mensaje,$data,425);
        }
    }
}

<?php

/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:05 PM
 */

namespace App\Core;

use App\Traits\ApiResponse;
use App\Traits\ManageRoles;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/** @property CrudService $service */

class CrudController extends BaseController
{
    use ApiResponse, ManageRoles;

    public $service;
    protected $validateStore = [];
    protected $validateUpdate = [];
    protected $validateDefault = [];
    protected $policies = [];
    protected $messages = [];

    public function __construct(CrudService $service)
    {
        $this->service = $service;
    }


    public function _index(Request $request)
    {

        try{
            $data = $this->service->_index($request);
        }catch(\Exception $e){
            return custom_error($e);
        }
        return custom_response(true,'index',$data);
    }

    public function _show($id)
    {
        try{
            $data = $this->service->_show($id);
            return custom_response(true, 'Show con éxito',$data);
        }catch(Exception $e){
            return custom_error($e,$e->getMessage());
        }
    }

    public function _store(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = $this->service->_store($request);
            DB::commit();
            Log::info('[created] '.json_encode($data));
            return custom_response(true, 'Creado con éxito',$data,201);
        }catch(Exception $e){
            DB::rollback();
            return custom_error($e,$e->getMessage());
        }
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function _update($id, Request $request)
    {
        DB::beginTransaction();
        try{
            $data = $this->service->_update($id, $request);
            DB::commit();
            Log::info('[updated] '.json_encode($data));
            return custom_response(true, 'Editado con éxito',$data,205);
        }catch(Exception $e){
            DB::rollback();
            return custom_error($e,$e->getMessage());
        }
    }

    public function _destroy($id, Request $request)
    {
        DB::beginTransaction();
        try{
            $data = $this->service->_destroy($id, $request);
            DB::commit();
            Log::info('[deleted] '.$data);
            return custom_response(true, 'Eliminado con éxito',$data,202);
        }catch(Exception $e){
            DB::rollback();
            return custom_error($e,$e->getMessage());
        }
    }

}

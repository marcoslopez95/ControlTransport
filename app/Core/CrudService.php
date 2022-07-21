<?php

/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:06 PM
 */

namespace App\Core;


use Carbon\Carbon;
use DomainException;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** @property CrudRepository $repository */
class CrudService
{

    protected $model;
    protected $object;
    protected $name = "item";
    protected $namePlural = "items";
    protected $paginate = false;
    protected $limit = null;
    protected $data = [];
    protected $request;
    protected $dato;
    protected $repository;

    public function __construct(CrudRepository $repository)
    {
        $this->repository = $repository;
    }


    public function _index(Request $request = null)
    {
        return $this->repository->_index($request);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * consultar un registro por medio de un id
     */
    public function _show($id, $request = null)
    {
        return $this->repository->_show($id,$request);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function _store(Request $request)
    {
        return $this->repository->_store($request->all());
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * acualizar registro
     */
    public function _update($id, Request $request)
    {

        return $this->repository->_update($id, $request->all());
    }

    /**
     * @param $id
     * @param $name_pk
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * metodo para eliminar un registro
     */
    public function _destroy($id)
    {
        return $this->repository->_destroy($id);
    }
}

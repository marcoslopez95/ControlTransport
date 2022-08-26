<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Gastos;


use App\Core\CrudService;
use App\Repositories\Gastos\GastosRepository;

/** @property GastosRepository $repository */
class GastosService extends CrudService
{

    protected $name = "gastos";
    protected $namePlural = "gastos";

    public function __construct(GastosRepository $repository)
    {
        parent::__construct($repository);
    }

}
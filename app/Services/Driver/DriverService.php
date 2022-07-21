<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Driver;


use App\Core\CrudService;
use App\Repositories\Driver\DriverRepository;

/** @property DriverRepository $repository */
class DriverService extends CrudService
{

    protected $name = "driver";
    protected $namePlural = "drivers";

    public function __construct(DriverRepository $repository)
    {
        parent::__construct($repository);
    }

}
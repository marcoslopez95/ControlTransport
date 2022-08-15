<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Travel;


use App\Core\CrudService;
use App\Repositories\Travel\TravelRepository;

/** @property TravelRepository $repository */
class TravelService extends CrudService
{

    protected $name = "travel";
    protected $namePlural = "travel";

    public function __construct(TravelRepository $repository)
    {
        parent::__construct($repository);
    }

}
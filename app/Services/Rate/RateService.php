<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Rate;


use App\Core\CrudService;
use App\Repositories\Rate\RateRepository;

/** @property RateRepository $repository */
class RateService extends CrudService
{

    protected $name = "rate";
    protected $namePlural = "rates";

    public function __construct(RateRepository $repository)
    {
        parent::__construct($repository);
    }

}
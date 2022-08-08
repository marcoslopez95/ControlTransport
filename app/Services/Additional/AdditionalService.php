<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Additional;


use App\Core\CrudService;
use App\Repositories\Additional\AdditionalRepository;

/** @property AdditionalRepository $repository */
class AdditionalService extends CrudService
{

    protected $name = "additional";
    protected $namePlural = "additionals";

    public function __construct(AdditionalRepository $repository)
    {
        parent::__construct($repository);
    }

}
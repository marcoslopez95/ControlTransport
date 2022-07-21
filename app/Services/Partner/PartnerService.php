<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Partner;


use App\Core\CrudService;
use App\Repositories\Partner\PartnerRepository;

/** @property RoleRepository $repository */
class PartnerService extends CrudService
{

    protected $name = "partner";
    protected $namePlural = "partners";

    public function __construct(PartnerRepository $repository)
    {
        parent::__construct($repository);
    }

}

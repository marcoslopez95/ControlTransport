<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Office;


use App\Core\CrudService;
use App\Repositories\Office\OfficeRepository;

/** @property OfficeRepository $repository */
class OfficeService extends CrudService
{

    protected $name = "office";
    protected $namePlural = "offices";

    public function __construct(OfficeRepository $repository)
    {
        parent::__construct($repository);
    }

}
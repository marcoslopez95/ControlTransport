<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Liquidation;


use App\Core\CrudService;
use App\Repositories\Liquidation\LiquidationRepository;

/** @property LiquidationRepository $repository */
class LiquidationService extends CrudService
{

    protected $name = "liquidation";
    protected $namePlural = "liquidations";

    public function __construct(LiquidationRepository $repository)
    {
        parent::__construct($repository);
    }

}
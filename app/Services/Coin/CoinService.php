<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Coin;


use App\Core\CrudService;
use App\Repositories\Coin\CoinRepository;

/** @property CoinRepository $repository */
class CoinService extends CrudService
{

    protected $name = "coin";
    protected $namePlural = "coins";

    public function __construct(CoinRepository $repository)
    {
        parent::__construct($repository);
    }

}
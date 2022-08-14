<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Additional;


use App\Core\CrudService;
use App\Models\Coin;
use App\Repositories\Additional\AdditionalRepository;
use Illuminate\Http\Request;

/** @property AdditionalRepository $repository */
class AdditionalService extends CrudService
{

    protected $name = "additional";
    protected $namePlural = "additionals";

    public function __construct(AdditionalRepository $repository)
    {
        parent::__construct($repository);
    }
    public function _show($id, $request = null)
    {
        $add = parent::_show($id);
        $add->load('coin');
        return $add;
    }

    public function _index(?Request $request = null)
    {
        $adds = $this->repository->_index($request);
        $adds->load('coin');
        return $adds;
    }

}

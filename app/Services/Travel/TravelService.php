<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Travel;


use App\Core\CrudService;
use App\Repositories\Travel\TravelRepository;
use Illuminate\Http\Request;

/** @property TravelRepository $repository */
class TravelService extends CrudService
{

    protected $name = "travel";
    protected $namePlural = "travel";

    public function __construct(TravelRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _index(?Request $request = null)
    {
        $travels = $this->repository->_index($request);

        $travels->load('vehicle');

        return $travels;
    }

    public function _show($id, $request = null)
    {
        $travel = $this->repository->_show($id);
        $travel->load(['vehicle','liquidations']);

        return $travel;
    }

    public function _update($id, Request $request)
    {
        return $this->repository->_update($id,$request->only('observation'));
    }

}

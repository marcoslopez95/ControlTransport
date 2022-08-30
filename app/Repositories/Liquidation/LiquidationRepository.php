<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Liquidation;

use App\Core\CrudRepository;
use App\Models\Liquidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/** @property Liquidation $model */
class LiquidationRepository extends CrudRepository
{

    public function __construct(Liquidation $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        if(Auth::user()->role_id == 1){
            $liquidations = $this->model::filter($request)->orderBy('id','desc')->get();
        }else{
            $liquidations = $this->model::filter($request)->socio()->orderBy('id','desc')->get();

        }
        return $liquidations;
    }

    public function saveAdditionals($id,$additionals){
        self::_show($id);
        return $this->object->additionals()->attach($additionals['additionals']);
    }

    public function saveAmount($id,$amount){
        self::_show($id);

        return $this->object->ammounts()->createMany($amount['ammounts']);
    }

    public function syncAdditionals($id,$additionals){
        self::_show($id);
        $this->object->additionals()->sync($additionals['additionals']);
    }

    public function updateAmount($id,$additionals){
        self::_show($id);
        $this->object->ammounts()->delete();
        self::saveAmount($id,$additionals);
    }

}

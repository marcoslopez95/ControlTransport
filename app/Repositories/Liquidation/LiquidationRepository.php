<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Liquidation;

use App\Core\CrudRepository;
use App\Models\Liquidation;
use Illuminate\Support\Facades\Log;

/** @property Liquidation $model */
class LiquidationRepository extends CrudRepository
{

    public function __construct(Liquidation $model)
    {
        parent::__construct($model);
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

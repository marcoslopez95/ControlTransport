<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:06 PM
 */

namespace App\Core;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
/** @property CrudModel $model */
class CrudRepository
{
    protected $model;
    public $data = [];
    protected $object;
    public function __construct($model = null)
    {
        /** @var CrudModel model */
        $this->model = $model;
    }

    public function _index($request = null, $user = null)
    {
        return $this->model::all();
    }


    public function _show($id)
    {
        $this->object = $this->model::findOrFail($id);
        return $this->object;
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function _store(array $data)
    {
        return $this->model::create($data);
    }

    public function _update($id, array $data)
    {
        self::_show($id);
        $this->object->update($data);
        self::_show($id);
        return $this->object;
    }

    public function _destroy($id){
        self::_show($id);
        return $this->object->delete();
    }

}

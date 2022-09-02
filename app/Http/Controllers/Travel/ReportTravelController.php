<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportTravelController extends Controller
{
    private $travels;
    private $objec;

    public function __construct(Travel $travels)
    {
        $this->travels = $travels;
    }

    public function __invoke(Request $request)
    {
        self::getTravels($request);
        $monedas = Coin::all();
        foreach($this->object as $travel){
            $monto_sum = [];
            $travel->montos->transform(function($item) use ($monedas){
                $moneda = $monedas->where('id',$item->coin_id)->first();

                return [
                    'coin_name' => $moneda->name,
                    'quantity'  => $item->quantity
                ];
            });

        }
        return $this->object;
    }

    private function getTravels(Request $request):void
    {
        $this->object = $this->travels::filter($request);
        if(Auth::user()->role_id !== 1){
            $this->object = $this->object->socio()->get()
            ;
        }else{
            $this->object = $this->object->get()
            ;
        }

        $this->object->load(['montos','vehicle','liquidations','gastos']);;
    }

}

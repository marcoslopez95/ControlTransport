<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use App\Models\Additional;
use App\Models\Coin;
use App\Models\Liquidation;
use App\Models\Office;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class ReportTravelController extends Controller
{
    private $travels;
    private $object;
    private $html;
    public function __construct(Travel $travels)
    {
        $this->travels = $travels;
    }

    public function __invoke(Request $request)
    {
        self::getTravels($request);
        self::ParserTravels();
        self::generateHtml();
        // return self::generatePdf();
        return $this->html;
    }

    private function generatePdf(){
        $pdf = new  Mpdf();
        $pdf->WriteHTML($this->html);
        $nombre_archivo = 'reporte-viaje-control-'. ($this->object->first())->vehicle->num_controller;
        header('Content-Type: application/pdf');
        header("Content-Disposition: inline; filename='$nombre_archivo.pdf'");
        return $pdf->Output("$nombre_archivo.pdf", 'I');

        $pdf->Output();

    }

    private function generateHtml(){
        $this->html = view('Travels.ReportTravel',[
            'travel' => $this->object->first()
        ]);
    }

    private function ParserTravels(){
        $monedas = Coin::all();
        $oficinas = Office::all();
        foreach ($this->object as $travel) {
            $monto_sum = [];
            $recorrido = '';
            $travel->montos->map(function ($item) use ($monedas, &$monto_sum) {
                $moneda = $monedas->where('id', $item->coin_id)->first();

                if (!array_key_exists($moneda->symbol, $monto_sum)) {
                    $monto_sum[$moneda->symbol] = 0;
                }
                $monto_sum[$moneda->symbol] += $item->quantity;
            });
            unset($travel['montos']);
            $travel['montos'] = $monto_sum;
            $travel->liquidations->map(function ($liquidation) use ($monedas){
                $liquidation->ammounts->transform(function($item) use ($monedas){
                    $moneda = $monedas->find($item['coin_id']);
                    $item['coin_symbol'] = $moneda->symbol;
                    return $item;
                });
                return $liquidation;
            });
            $travel->liquidations->transform(function (Liquidation $liquidation) use ($oficinas, &$recorrido) {
                $office_start = $oficinas->find($liquidation->office_origin);
                $office_end = $oficinas->find($liquidation->office_destiny);

                $liquidation->office_origin_name = $office_start->name;
                $liquidation->office_destiny_name = $office_end->name;
                $recorrido .= $office_start->name . '/' . $office_end->name . '. ';

                $acum = 0;
                $base = $liquidation->precio_pasaje * $liquidation->pasajeros;
                foreach ($liquidation->additionals as $additional) {
                    $desc_ammount = 0;
                    if (!$additional->quantity && $additional->percent > 0) {
                        $desc_ammount = ($additional->percent / 100);
                        $acum += $base * $desc_ammount;
                    } else
                    if ($additional->quantity > 0 && !$additional->percent) {

                        $acum += $additional->quantity;
                    }
                }
                $liquidation->gastos_cantidad = $base;
                unset($liquidation['additionals']);
                return $liquidation;
            });


            $travel['recorrido'] = $recorrido;

            $caja = [];

            foreach ($travel->montos as $key => $value) {
                $caja[$key] = $value;
            }

            foreach ($travel->gastos as $gasto) {
                if (array_key_exists($gasto->coin->symbol, $caja)) {
                    $caja[$gasto->coin->symbol] -= $gasto->quantity;
                }
            }

            $travel['caja'] = $caja;
        }
    }
    private function getTravels(Request $request): void
    {
        $this->object = $this->travels::filter($request);
        // if (Auth::user()->role_id !== 1) {
        //     $this->object = $this->object->socio()->get();
        // } else {
            $this->object = $this->object->get();
        // }

        $this->object->load([
            'montos',
            'vehicle',
            'liquidations.additionals',
            'liquidations.ammounts',
            'gastos.coin'
        ]);
    }
}

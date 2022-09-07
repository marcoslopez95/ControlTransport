@extends('base')
@section('styles')
    <style>
        table {
            border-collapse: collapse
        }

        th,
        td {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            padding: 0px 10px;
        }
    </style>
@endsection
@section('body')
    <div>
        <h3 align='center'>Recepcion de Viaje</h3>
        <div align='center'>
            <b>Control {{ $travel->vehicle->num_control }}</b><br>
            {{ $travel->recorrido }}
        </div>
        <br>
        <div>
            <div style="float: left">

                <b>Fecha de Salida: {{ $travel->date_start }}</b>
            </div>
            <div style="float: right">
                <b>Fecha de Llegada: {{ $travel->date_end }}</b>
            </div>
        </div>
    </div>

    <table align="center" style="margin-top: 25px">
        <thead>

            <tr>
                <th>
                    Ofc. Salida
                </th>
                <th>
                    Ofc. Llegada
                </th>
                <th>
                    Pasajeros
                </th>
                <th>
                    Monto $
                </th>
                <th>
                    Deducciones
                </th>
                <th>
                    Neto
                </th>
                @foreach ($travel->montos as $key => $value)
                    <th>
                        {{ $key }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $acum_total = 0;
                $acum_coins = [];
            @endphp
            @foreach ($travel->liquidations as $liquidation)
                @php
                    $acum_total += $liquidation->pasajeros * $liquidation->precio_pasaje;
                @endphp
                <tr>
                    <td align="center">
                        {{ $liquidation->office_origin_name }}
                    </td>
                    <td align="center">
                        {{ $liquidation->destiny_name }}
                    </td>
                    <td align="center">
                        {{ $liquidation->pasajeros }}
                    </td>
                    <td align="center">
                        {{ $liquidation->pasajeros * $liquidation->precio_pasaje }}
                    </td>
                    <td align="center">
                        {{ $liquidation->gastos_cantidad }}
                    </td>
                    <td align="center">
                        {{ $liquidation->total }}
                    </td>
                    @foreach ($travel->montos as $key => $value)
                        @php
                            $item = $liquidation->ammounts->where('coin_symbol', $key)->first();
                            if (!array_key_exists($key, $acum_coins)) {
                                $acum_coins[$key] = 0;
                            }
                            $acum_coins[$key] += $item->quantity;
                        @endphp
                        <td align="center">
                            {{ $item->quantity }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2"> Totales </th>
                <th>{{ $travel->liquidations->sum('pasajeros') }}</th>
                <th>{{ $acum_total }}</th>
                <th>{{ $travel->liquidations->sum('gastos_cantidad') }}</th>
                <th>{{ $travel->liquidations->sum('total') }}</th>
                @foreach ($travel->montos as $key => $monto)
                    <th>
                        {{ $acum_coins[$key] }}
                    </th>
                @endforeach

            </tr>
        </tfoot>
    </table>
    <div style="height: 35px"></div>
    <div style="display: flex">
        <div style='' width='350px'>

            {{-- gastos --}}
            <div><b>Gastos</b></div>
            <table>
                <thead>
                    <tr>
                        <th>
                            Descripción
                        </th>
                        <th>
                            Cantidad
                        </th>
                        <th>
                            Moneda
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($travel->gastos as $gasto)
                        <tr>
                            <td>{{ $gasto->description }}</td>

                            <td>{{ $gasto->quantity }}</td>

                            <td>{{ $gasto->coin->symbol }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="width: 20%"></div>
        <div style='margin-right: 0; ' >

            {{-- gastos --}}
            <div><b>En caja</b></div>
            <table>
                <thead>
                    <tr>
                        <th>
                            Cantidad
                        </th>
                        <th>
                            Moneda
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($travel->caja as $symbol => $gasto)
                        <tr>
                            <td>{{ $symbol }}</td>

                            <td>{{ $gasto }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        <footer style="margin-top:15px; text-align: left;" align='left'>
            Observaciones: {{ $travel->observation }}
        </footer>
    @endsection

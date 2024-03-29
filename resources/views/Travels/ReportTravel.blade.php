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
                <th>
                    Por Pagar
                </th>
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
                        {{ $liquidation->office_destiny_name }}
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
                    <td align="center">
                        {{ $liquidation->falta }}
                    </td>
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
                <th>{{ $travel->liquidations->sum('falta') }}</th>
            </tr>
        </tfoot>
    </table>
    <div style="height: 35px"></div>


    <table style="width: 100%">
        <tr>
            <td style="border: 0">
                <table>
                    <thead>
                        <tr>
                            <th colspan="3">Gastos</th>
                        </tr>
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
            </td>
            <td style="border: 0">
                {{-- gastos --}}
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">
                                En caja
                            </th>
                        </tr>
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
            </td>
        </tr>
    </table>
    {{-- gastos --}}





    <footer style="margin-top:15px; text-align: left;" align='left'>
    <table align="center">
        <tr>
            <td align="center" colspan="3"><b>Choferes</b></td>
        </tr>
        <tr>
            <td><b>Nombre</b></td>
            <td><b>Apellido</b></td>
            <td><b>Cedula</b></td>
        </tr>
        @foreach ($travel->drivers as $driver)
        <tr>
            <td>{{$driver->first_name}}</td>
            <td>{{$driver->last_name}}</td>
            <td>{{$driver->ci}}</td>
        </tr>
        @endforeach

    </table>
    <br>
        Observaciones: {{ $travel->observation }}
    </footer>
@endsection

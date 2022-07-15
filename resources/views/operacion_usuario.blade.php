@extends('FormatoReporte')

@section('table')
    @foreach ($data as $operation)
        <tr>
            <td>{{$operation->id}}</td>
            <td>{{$operation->code}}</td>
            <td>{{$operation->type}}</td>
            <td>{{$operation->date}}</td>
            <td>{{$operation->description}}</td>
            <td>{{$operation->point}}</td>
        </tr>
        <tr>
            <td colspan="{{count($cab)}}" class="align-center contenedores">
                Lista de Contenedores del Vehículo {{$vehicle->vehicleID}}
            </td>
        </tr>
        @if (count($vehicle->container) > 0 )
            <tr class="align-center">
                <td colspan="2" class='negrita'>Id</td>
                <td colspan="2" class='negrita'>Código</td>
                <td colspan="2" class='negrita'>Operación</td>
                <td colspan="2" class='negrita'>Fecha</td>
                <td colspan="2" class='negrita'>Observación</td>
                <td colspan="2" class='negrita'>Puntos</td>
                <td colspan="2" class='negrita'>Sucursal</td>,
                <td colspan="2" class='negrita'>Cliente</td>,
                <td colspan="2" class='negrita'>Producto</td>,
                <td colspan="2" class='negrita'>Value</td>
            </tr>
        @else
            <tr>
                <td colspan="{{count($header)}}">
                    Sin operaciones
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="{{count($header)}}" class="espaciado2"></td>
        </tr>
    @endforeach
@endsection

@section('estilos')
<style  type='text/css'>
    .contenedores{
        background-color: rgba(221, 220, 212);
    }

    .negrita{
        font-weight: bold;
    }
    .cabecera{
        background-color: rgb(31, 180, 250);
        color: white;
        font-weight: bold;
    }
    table{
        font-size: 12px;
    }
    .agencia{
        font-size: 18px;
    }
    .cobrador{
        font-size: 14px;
    }
    .espaciado{
        height: 10px;
    }
    .espaciado2{
        height: 30px;
    }
    .doble-borde{
        border-top-style: solid;
        border-bottom-style: solid;
        border-top: 1px black;
        border-bottom: 2px black;
        border-left: none;
        border-right: none;
    }
</style>

@endsection
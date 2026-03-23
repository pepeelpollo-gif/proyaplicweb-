@extends('principal')

@section('contenido')

<h1>Reporte General de Citas</h1>
<br>

<table border="1">
    <tr>
        <td>IDC</td>
        <td>Nombre</td>
        <td>Apellido</td>
        <td>Teléfono</td>
        <td>Fecha</td>
        <td>Hora</td>
        <td>Género</td>
        <td>No. Servicios</td>
    </tr>
    @foreach($reporte as $r)
    <tr>
        <td>{{ $r->idc }}</td>
        <td>{{ $r->nombre }}</td>
        <td>{{ $r->ap }}</td>
        <td>{{ $r->telefono }}</td>
        <td>{{ $r->fecha }}</td>
        <td>{{ $r->hora }}</td>
        <td>{{ $r->genero }}</td>
        <td>{{ $r->num_servicios }}</td>
    </tr>
    @endforeach
</table>

@stop

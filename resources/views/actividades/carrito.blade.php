<table border =1>
    <tr><td>Tipo</td>
        <td>Actividad</td>
        <td>Horas</td>
        <td>Total a pagar</td>
        <td>Partes</td>
        <td>Detalle</td></tr>
    @foreach ($acticarrito as $ac)
    <tr><td>{{$ac->tipo}}</td>
        <td>{{$ac->acti}}</td>
        <td>{{$ac->horas}}</td>
        <td>{{$ac->total}}</td>
        <td>{{$ac->partes}}</td>
        <td>{{$ac->detalle}}</td></tr>
    @endforeach
</table>
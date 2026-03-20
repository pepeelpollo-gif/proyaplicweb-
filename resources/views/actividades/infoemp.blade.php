<table border="2">
    <tr><td>Foto<br>
    <img src="{{ url('archivos/',$empleado->foto)}}" height="50" width="50">
    Edad
    {{ $empleado->edad }}
    <br>Correo{{ $empleado->correo }}
    <br>rfc{{ $empleado->rfc }}
</td></tr>
</table>
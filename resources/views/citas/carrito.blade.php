<table border="1">
    <tr>
        <td>Género</td>
        <td>Largo</td>
        <td>Tipo Corte</td>
        <td>Flequillo</td>
        <td>Estilo</td>
        <td>Servicio Add.</td>
        <td>Eliminar</td>
    </tr>
    @foreach($carrito as $item)
    <tr>
        <td>{{ $item->genero }}</td>
        <td>{{ $item->largo }}</td>
        <td>{{ $item->corte }}</td>
        <td>{{ $item->flequillo }}</td>
        <td>{{ $item->estilo }}</td>
        <td>{{ $item->servicio }}</td>
        <td>
            <button onclick="
                $('#carrito').load(
                    '{{ url('eliminadetalle') }}' +
                    '?idd={{ $item->idd }}&idac={{ $item->idac }}'
                ); return false;">
                Eliminar
            </button>
        </td>
    </tr>
    @endforeach
</table>
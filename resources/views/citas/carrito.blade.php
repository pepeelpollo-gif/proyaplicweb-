<table border="1">
    <tr>
        <td>Género</td>
        <td>Largo</td>
        <td>Tipo Corte</td>
        <td>Flequillo</td>
        <td>Estilo</td>
        <td>Servicio Add.</td>
    </tr>
    @foreach($carrito as $item)
    <tr>
        <td>{{ $item->genero }}</td>
        <td>{{ $item->largo }}</td>
        <td>{{ $item->corte }}</td>
        <td>{{ $item->flequillo }}</td>
        <td>{{ $item->estilo }}</td>
        <td>{{ $item->servicio }}</td>
    </tr>
    @endforeach
</table>

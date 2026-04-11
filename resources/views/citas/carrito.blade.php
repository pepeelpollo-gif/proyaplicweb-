<div class="spa-carrito-wrapper">
    <table class="spa-carrito-table">
        <thead>
            <tr>
                <th>Género</th>
                <th>Largo</th>
                <th>Corte</th>
                <th>Flequillo</th>
                <th>Estilo</th>
                <th>Servicio Add.</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($carrito as $item)
            <tr>
                <td>{{ $item->genero }}</td>
                <td>{{ $item->largo }}</td>
                <td>{{ $item->corte }}</td>
                <td>{{ $item->flequillo }}</td>
                <td>{{ $item->estilo }}</td>
                <td>{{ $item->servicio }}</td>
                <td style="text-align:center;">
                    <button class="spa-btn-x" title="Eliminar" onclick="
                        $('#carrito').load(
                            '{{ url('eliminadetalle') }}' +
                            '?idd={{ $item->idd }}&idac={{ $item->idac }}'
                        ); return false;">
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:24px; color:var(--gris-med); font-style:italic; font-size:13px;">
                    El carrito está vacío.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

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
                    <button class="spa-btn-x btn-eliminar-detalle"
                            data-idd="{{ $item->idd }}"
                            data-idac="{{ $item->idac }}"
                            data-url="{{ url('eliminadetalle') }}"
                            title="Eliminar">
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

<script>
document.querySelectorAll('.btn-eliminar-detalle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var idd  = this.getAttribute('data-idd');
        var idac = this.getAttribute('data-idac');
        var url  = this.getAttribute('data-url');
        $('#carrito').load(url + '?idd=' + idd + '&idac=' + idac);
    });
});
</script>

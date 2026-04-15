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
                    <button type="button" class="spa-btn-x btn-eliminar-detalle"
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

$(document).off('click', '.btn-eliminar-detalle').on('click', '.btn-eliminar-detalle', function(e) {
    e.preventDefault();
    var idd  = $(this).attr('data-idd');
    var idac = $(this).attr('data-idac');
    var url  = $(this).attr('data-url');
    
    var targetDiv = $('#carrito-modificar').length > 0 ? '#carrito-modificar' : '#carrito';
    
    $(targetDiv).load(url + '?idd=' + idd + '&idac=' + idac);
});

@if(isset($idac))
    if(document.getElementById('idac_actual')) {
        document.getElementById('idac_actual').value = '{{ $idac }}';
        
        $('#select-cliente, input[name="fecha"], input[name="hora"], #idtc').css({'pointer-events': 'none', 'background-color': '#f0f0f0'});
        $('#telefono, #nombre, #ap').prop('readonly', true).css('background-color', '#f0f0f0');
        
        $('#btn-nueva-cita').show();
    }
@endif
</script>
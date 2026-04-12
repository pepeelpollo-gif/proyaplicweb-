@extends('principal')

@section('contenido')
<link rel="stylesheet" href="{{ asset('css/spa.css') }}">

<script type="text/javascript">
$(document).ready(function(){
    /* --- MANEJO DE UI POR GÉNERO --- */
    function mostrarGenero(g){
        $("#bloque-hombre, #bloque-mujer, #bloque-servicios-h, #bloque-servicios-m").addClass('spa-hidden');
        if(g == 1) $("#bloque-hombre, #bloque-servicios-h").removeClass('spa-hidden');
        else if(g == 2) $("#bloque-mujer, #bloque-servicios-m").removeClass('spa-hidden');
    }

    mostrarGenero($("#idtc").val());
    $("#idtc").change(function(){ mostrarGenero(this.value); });

    /* --- ELIMINAR SERVICIO (AJAX) --- */
    $(document).on("click", ".btn-eliminar-servicio", function(){
        var $btn = $(this), idd = $btn.data("idd"), idac = $btn.data("idac"), $fila = $btn.closest("tr");
        $fila.addClass("spa-fila-eliminando");

        setTimeout(function(){
            $fila.remove();
            var total = $("#tabla-servicios-mod tbody tr").length;
            $("#contador-servicios").text(total + " servicio(s)");
            
            if (total === 0) {
                $("#tabla-servicios-mod").closest(".spa-carrito-wrapper").replaceWith('<div id="msg-sin-servicios" class="spa-sin-servicios">Esta cita no tiene servicios registrados aún.</div>');
            }
            $.ajax({ url: '{{ url("eliminadetalle") }}', method: 'GET', data: { idd: idd, idac: idac } });
        }, 220);
    });
});
</script>

<div class="spa-header">
    <div><div class="spa-tag">Aura Spa Harmony</div><h1>Modificar <em>Cita</em></h1></div>
    <div style="margin-left:auto;"><a href="{{ route('reportecitas') }}" class="spa-btn spa-btn-outline" style="text-decoration:none; font-size:13px;">← Volver al reporte</a></div>
</div>

<div class="spa-container">
<form action="{{ route('guardamodifica') }}" method="POST">
    @csrf
    <input type="hidden" name="idac" value="{{ $cita->idac }}">

    <div class="spa-section">
        <div class="spa-section-label">Datos del Cliente</div>
        <div class="spa-grid">
            <div class="spa-field"><label class="spa-label">IDC</label><input type="text" class="spa-input" value="{{ $cita->idc }}" readonly></div>
            <div class="spa-field"><label class="spa-label">Teléfono</label><input type="text" class="spa-input" value="{{ $cita->telefono }}" readonly></div>
            <div class="spa-field"><label class="spa-label">Nombre</label><input type="text" class="spa-input" value="{{ $cita->nombre }}" readonly></div>
            <div class="spa-field"><label class="spa-label">Apellido</label><input type="text" class="spa-input" value="{{ $cita->ap }}" readonly></div>
        </div>
    </div>

    <div class="spa-section">
        <div class="spa-section-label">Detalles de la Cita</div>
        <div class="spa-grid cols-3">
            <div class="spa-field">
                <label class="spa-label">Género</label>
                <select name="idtc" id="idtc" class="spa-select">
                    @foreach($tiposcliente as $t)<option value="{{ $t->idtc }}" {{ $t->idtc == $cita->idtc ? 'selected' : '' }}>{{ $t->tipo_cliente }}</option>@endforeach
                </select>
            </div>
            <div class="spa-field"><label class="spa-label">Fecha</label><input type="date" name="fecha" class="spa-input" value="{{ $cita->fecha }}"></div>
            <div class="spa-field"><label class="spa-label">Hora</label><input type="time" name="hora" class="spa-input" value="{{ $cita->hora }}"></div>
        </div>
    </div>

    <div id="bloque-hombre" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicio — Hombre</div>
        <div class="spa-grid">
            <div class="spa-field"><label class="spa-label">Largo</label><select name="idlch" class="spa-select"><option value="">— Seleccionar —</option>@foreach($largosh as $l)<option value="{{ $l->idlcm }}" {{ $detalle && $detalle->idlcm == $l->idlcm ? 'selected' : '' }}>{{ $l->largo }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Corte</label><select name="idtch" class="spa-select"><option value="">— Seleccionar —</option>@foreach($cortesh as $c)<option value="{{ $c->idtch }}" {{ $detalle && $detalle->idtch == $c->idtch ? 'selected' : '' }}>{{ $c->corte }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Estilo</label><select name="idec" class="spa-select"><option value="">— Seleccionar —</option>@foreach($estilos as $e)<option value="{{ $e->idec }}" {{ $detalle && $detalle->idec == $e->idec ? 'selected' : '' }}>{{ $e->estilo }}</option>@endforeach</select></div>
        </div>
    </div>

    <div id="bloque-mujer" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicio — Mujer</div>
        <div class="spa-grid">
            <div class="spa-field"><label class="spa-label">Largo</label><select name="idlcm" class="spa-select"><option value="">— Seleccionar —</option>@foreach($largosm as $l)<option value="{{ $l->idlcm }}" {{ $detalle && $detalle->idlcm == $l->idlcm ? 'selected' : '' }}>{{ $l->largo }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Corte</label><select name="idtcm" class="spa-select"><option value="">— Seleccionar —</option>@foreach($cortesm as $c)<option value="{{ $c->idtch }}" {{ $detalle && $detalle->idtch == $c->idtch ? 'selected' : '' }}>{{ $c->corte }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Flequillo</label><select name="idf" class="spa-select"><option value="">Sin flequillo</option>@foreach($flequillos as $f)<option value="{{ $f->idf }}" {{ $detalle && $detalle->idf == $f->idf ? 'selected' : '' }}>{{ $f->flequillo }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Estilo</label><select name="idec" class="spa-select"><option value="">— Seleccionar —</option>@foreach($estilos as $e)<option value="{{ $e->idec }}" {{ $detalle && $detalle->idec == $e->idec ? 'selected' : '' }}>{{ $e->estilo }}</option>@endforeach</select></div>
        </div>
    </div>

    <div class="spa-actions">
        <button type="submit" class="spa-btn">Guardar cambios</button>
        <a href="{{ route('reportecitas') }}" class="spa-btn spa-btn-outline" style="text-decoration:none;">Cancelar</a>
    </div>
</form>

<div style="margin-top: 40px;">
    <div style="display:flex; align-items:center; gap:14px; margin-bottom:14px;">
        <div class="spa-section-label" style="margin-bottom:0; flex:1;">Servicios registrados en esta cita</div>
        <span id="contador-servicios" style="background: var(--carbon); color: var(--blanco); font-size: 12px; font-weight: 600; padding: 3px 12px; border-radius: 20px;">{{ count($todosDetalles) }} servicio(s)</span>
    </div>

    @if(count($todosDetalles) > 0)
    <div class="spa-carrito-wrapper">
        <table class="spa-carrito-table" id="tabla-servicios-mod">
            <thead><tr><th>#</th><th>Género</th><th>Largo</th><th>Corte</th><th>Flequillo</th><th>Estilo</th><th>Servicio Add.</th><th style="text-align:center;">Acciones</th></tr></thead>
            <tbody>
                @foreach($todosDetalles as $i => $d)
                <tr id="fila-servicio-{{ $d->idd }}">
                    <td style="color:var(--gris-med); font-size:12px;">{{ $i + 1 }}</td>
                    <td>{{ $d->genero }}</td><td>{{ $d->largo }}</td><td>{{ $d->corte }}</td>
                    <td>{{ $d->flequillo }}</td><td>{{ $d->estilo }}</td><td>{{ $d->servicio }}</td>
                    <td style="text-align:center;"><button type="button" class="spa-btn-x btn-eliminar-servicio" data-idd="{{ $d->idd }}" data-idac="{{ $d->idac }}" title="Eliminar servicio"></button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div id="msg-sin-servicios" class="spa-sin-servicios">Esta cita no tiene servicios registrados aún.</div>
    @endif
</div>
</div>
@stop
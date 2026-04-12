@extends('principal')

@section('contenido')
<link rel="stylesheet" href="{{ asset('css/spa.css') }}">

<script type="text/javascript">
$(document).ready(function(){

    /* --- VALIDACIÓN EN TIEMPO REAL --- */
    $("#nombre, #ap").on("input", function(){
        $(this).val($(this).val().replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, ""));
        validarCampo($(this));
    });

    $("#telefono").on("input", function(){
        $(this).val($(this).val().replace(/\D/g, "").slice(0, 10));
        validarCampo($(this));
    });

    $(".spa-input[data-validar]").on("blur", function(){ validarCampo($(this)); });

    function validarCampo($input) {
        var id = $input.attr("id"), val = $.trim($input.val()), msg = "";
        if ((id === "nombre" || id === "ap") && (val === "" || !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(val))) msg = "Inválido.";
        if (id === "telefono" && (val === "" || !/^\d{10}$/.test(val))) msg = "Debe tener 10 dígitos.";
        
        if (msg) { $input.addClass("spa-input-error"); $("#err-" + id).text(msg).removeClass("spa-hidden"); } 
        else { $input.removeClass("spa-input-error"); $("#err-" + id).addClass("spa-hidden").text(""); }
        return msg === "";
    }

    /* --- VALIDACIÓN FINAL ANTES DE ENVIAR (AQUÍ ESTABA EL ERROR) --- */
    function validarFormulario() {
        var ok = true;
        // 1. Validar texto
        ["nombre", "ap", "telefono"].forEach(function(id){
            var $input = $("#" + id);
            if ($input.length && !$input.prop("readonly") && !validarCampo($input)) ok = false;
        });

        // 2. Validar selects de Cita obligatorios
        if ($("#idtc").val() === "") { alert("Por favor selecciona el Género."); ok = false; }
        else if ($("input[name='fecha']").val() === "") { alert("Por favor selecciona la Fecha."); ok = false; }
        else if ($("input[name='hora']").val() === "") { alert("Por favor selecciona la Hora."); ok = false; }

        return ok;
    }

    /* --- AUTOCOMPLETADO Y DINAMISMO UI --- */
    $("#select-cliente").change(function(){
        var opt = this.options[this.selectedIndex];
        if(opt.value != ""){
            $("#idc").val(opt.getAttribute('data-idc'));
            $("#nombre").val(opt.getAttribute('data-nombre'));
            $("#ap").val(opt.getAttribute('data-ap'));
            $("#telefono").val(opt.getAttribute('data-telefono'));
        } else {
            $("#idc").val("{{ $sigue }}");
            $("#nombre").val(""); $("#ap").val(""); $("#telefono").val("");
        }
        ["nombre", "ap", "telefono"].forEach(id => { $("#err-" + id).addClass("spa-hidden"); });
    });

    $("#idtc").change(function(){
        var g = this.value;
        $("#bloque-hombre, #bloque-mujer, #bloque-servicios-h, #bloque-servicios-m").addClass('spa-hidden');
        if(g == 1) $("#bloque-hombre, #bloque-servicios-h").removeClass('spa-hidden');
        else if(g == 2) $("#bloque-mujer, #bloque-servicios-m").removeClass('spa-hidden');
    });

    $("#btn-agregar").click(function(){
        if (!validarFormulario()) return; // Detiene si faltan datos importantes
        $("#carrito").load('{{ url("cargacarritocitas") }}?' + $("#form-cita").serialize());
    });
});
</script>

<div class="spa-header">
    <div><div class="spa-tag">Aura Spa Harmony</div><h1>Agendar <em>Cita</em></h1></div>
    <div style="margin-left:auto;"><a href="{{ route('reportecitas') }}" class="spa-btn spa-btn-outline" style="text-decoration:none; font-size:13px;">Ver reporte</a></div>
</div>

<div class="spa-container">
<form id="form-cita">
    <div class="spa-section">
        <div class="spa-section-label">Datos del Cliente</div>
        <div class="spa-grid cols-1" style="margin-bottom:16px;">
            <div class="spa-field">
                <select id="select-cliente" class="spa-select">
                    <option value="">— Nuevo cliente —</option>
                    @foreach($clientes as $cl)
                        <option value="{{ $cl->idc }}" data-idc="{{ $cl->idc }}" data-nombre="{{ $cl->nombre }}" data-ap="{{ $cl->ap }}" data-telefono="{{ $cl->telefono }}">{{ $cl->nombre }} {{ $cl->ap }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="spa-grid">
            <div class="spa-field"><label class="spa-label">IDC</label><input type="text" name="idc" id="idc" class="spa-input" value="{{ $sigue }}" readonly></div>
            <div class="spa-field"><label class="spa-label">Teléfono *</label><input type="tel" name="telefono" id="telefono" class="spa-input" maxlength="10" data-validar="true"><span id="err-telefono" class="spa-error-msg spa-hidden"></span></div>
            <div class="spa-field"><label class="spa-label">Nombre *</label><input type="text" name="nombre" id="nombre" class="spa-input" data-validar="true"><span id="err-nombre" class="spa-error-msg spa-hidden"></span></div>
            <div class="spa-field"><label class="spa-label">Apellido Paterno *</label><input type="text" name="ap" id="ap" class="spa-input" data-validar="true"><span id="err-ap" class="spa-error-msg spa-hidden"></span></div>
        </div>
    </div>

    <div class="spa-section">
        <div class="spa-section-label">Detalles de la Cita</div>
        <div class="spa-grid cols-3">
            <div class="spa-field">
                <label class="spa-label">Género *</label>
                <select name="idtc" id="idtc" class="spa-select">
                    <option value="">— Seleccionar —</option>
                    @foreach($tiposcliente as $t)<option value="{{ $t->idtc }}">{{ $t->tipo_cliente }}</option>@endforeach
                </select>
            </div>
            <div class="spa-field"><label class="spa-label">Fecha *</label><input type="date" name="fecha" class="spa-input"></div>
            <div class="spa-field"><label class="spa-label">Hora *</label><input type="time" name="hora" class="spa-input"></div>
        </div>
    </div>

    <div id="bloque-hombre" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicio — Hombre</div>
        <div class="spa-grid">
            <div class="spa-field"><label class="spa-label">Largo del Cabello</label><select name="idlch" class="spa-select"><option value="">— Seleccionar —</option>@foreach($largosh as $l)<option value="{{ $l->idlcm }}">{{ $l->largo }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Tipo de Corte</label><select name="idtch" class="spa-select"><option value="">— Seleccionar —</option>@foreach($cortesh as $c)<option value="{{ $c->idtch }}">{{ $c->corte }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Estilo</label><select name="idec" class="spa-select"><option value="">— Seleccionar —</option>@foreach($estilos as $e)<option value="{{ $e->idec }}">{{ $e->estilo }}</option>@endforeach</select></div>
        </div>
    </div>

    <div id="bloque-mujer" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicio — Mujer</div>
        <div class="spa-grid">
            <div class="spa-field"><label class="spa-label">Largo del Cabello</label><select name="idlcm" class="spa-select"><option value="">— Seleccionar —</option>@foreach($largosm as $l)<option value="{{ $l->idlcm }}">{{ $l->largo }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Tipo de Corte</label><select name="idtcm" class="spa-select"><option value="">— Seleccionar —</option>@foreach($cortesm as $c)<option value="{{ $c->idtch }}">{{ $c->corte }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Flequillo</label><select name="idf" class="spa-select"><option value="">Sin flequillo</option>@foreach($flequillos as $f)<option value="{{ $f->idf }}">{{ $f->flequillo }}</option>@endforeach</select></div>
            <div class="spa-field"><label class="spa-label">Estilo</label><select name="idec" class="spa-select"><option value="">— Seleccionar —</option>@foreach($estilos as $e)<option value="{{ $e->idec }}">{{ $e->estilo }}</option>@endforeach</select></div>
        </div>
    </div>

    <div id="bloque-servicios-h" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicios Adicionales</div>
        <div class="spa-radio-group">
            @foreach($servicios as $s) @if($s->ids != 4)<label class="spa-radio-option"><input type="radio" name="ids" value="{{ $s->ids }}">{{ $s->servicio }}</label>@endif @endforeach
            <label class="spa-radio-option"><input type="radio" name="ids" value="" checked> Ninguno</label>
        </div>
    </div>
    <div id="bloque-servicios-m" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicios Adicionales</div>
        <div class="spa-radio-group">
            @foreach($servicios as $s) @if($s->ids != 3)<label class="spa-radio-option"><input type="radio" name="ids" value="{{ $s->ids }}">{{ $s->servicio }}</label>@endif @endforeach
            <label class="spa-radio-option"><input type="radio" name="ids" value="" checked> Ninguno</label>
        </div>
    </div>

    <div class="spa-actions"><button type="button" id="btn-agregar" class="spa-btn">+ Agregar al carrito</button></div>
</form>

<div style="margin-top:32px;"><div class="spa-section-label" style="margin-bottom:12px;">Carrito de servicios</div><div id="carrito"></div></div>
</div>
@stop
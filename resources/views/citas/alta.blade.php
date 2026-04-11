@extends('principal')

@section('contenido')

<link rel="stylesheet" href="{{ asset('css/spa.css') }}">

<script type="text/javascript">
$(document).ready(function(){

    /* ─────────────────────────────────────────────
       VALIDACIÓN EN TIEMPO REAL
       ───────────────────────────────────────────── */

    // NOMBRE: solo letras, espacios y acentos — bloquea números y especiales
    $("#nombre, #ap").on("input", function(){
        // Elimina cualquier carácter que NO sea letra o espacio
        var limpio = $(this).val().replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, "");
        $(this).val(limpio);
        validarCampo($(this));
    });

    // TELÉFONO: solo dígitos, máximo 10
    $("#telefono").on("input", function(){
        var limpio = $(this).val().replace(/\D/g, "").slice(0, 10);
        $(this).val(limpio);
        validarCampo($(this));
    });

    // Validar al perder el foco también
    $(".spa-input[data-validar]").on("blur", function(){
        validarCampo($(this));
    });

    /* ─────────────────────────────────────────────
       FUNCIÓN DE VALIDACIÓN DE CAMPO
       ───────────────────────────────────────────── */
    function validarCampo($input) {
        var id  = $input.attr("id");
        var val = $.trim($input.val());
        var msg = "";

        if (id === "nombre" || id === "ap") {
            if (val === "") {
                msg = "Este campo es obligatorio.";
            } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(val)) {
                msg = "Solo se permiten letras y espacios.";
            }
        }

        if (id === "telefono") {
            if (val === "") {
                msg = "El teléfono es obligatorio.";
            } else if (!/^\d{10}$/.test(val)) {
                msg = "Debe contener exactamente 10 dígitos.";
            }
        }

        mostrarError($input, msg);
        return msg === "";
    }

    /* ─────────────────────────────────────────────
       MOSTRAR / OCULTAR MENSAJE DE ERROR
       ───────────────────────────────────────────── */
    function mostrarError($input, msg) {
        var $err = $("#err-" + $input.attr("id"));
        if (msg) {
            $input.addClass("spa-input-error");
            $err.text(msg).removeClass("spa-hidden");
        } else {
            $input.removeClass("spa-input-error");
            $err.addClass("spa-hidden").text("");
        }
    }

    /* ─────────────────────────────────────────────
       VALIDAR FORMULARIO COMPLETO ANTES DE AGREGAR
       ───────────────────────────────────────────── */
    function validarFormulario() {
        var ok = true;

        // Validar nombre, apellido y teléfono
        ["nombre", "ap", "telefono"].forEach(function(id){
            var $input = $("#" + id);
            if ($input.length && !$input.prop("readonly")) {
                if (!validarCampo($input)) ok = false;
            }
        });

        return ok;
    }

    /* ─────────────────────────────────────────────
       SELECT CLIENTE — autorellenar campos
       ───────────────────────────────────────────── */
    $("#select-cliente").change(function(){
        var opt = this.options[this.selectedIndex];
        if(opt.value != ""){
            $("#idc").val(opt.getAttribute('data-idc'));
            $("#nombre").val(opt.getAttribute('data-nombre'));
            $("#ap").val(opt.getAttribute('data-ap'));
            $("#telefono").val(opt.getAttribute('data-telefono'));
        } else {
            $("#idc").val("{{ $sigue }}");
            $("#nombre").val("");
            $("#ap").val("");
            $("#telefono").val("");
        }
        // Limpiar errores al seleccionar cliente
        ["nombre", "ap", "telefono"].forEach(function(id){
            mostrarError($("#" + id), "");
        });
    });

    /* ─────────────────────────────────────────────
       CAMBIO DE GÉNERO
       ───────────────────────────────────────────── */
    $("#idtc").change(function(){
        var g = this.value;
        $("#bloque-hombre, #bloque-mujer, #bloque-servicios-h, #bloque-servicios-m").addClass('spa-hidden');
        if(g == 1){
            $("#bloque-hombre, #bloque-servicios-h").removeClass('spa-hidden');
        } else if(g == 2){
            $("#bloque-mujer, #bloque-servicios-m").removeClass('spa-hidden');
        }
    });

    /* ─────────────────────────────────────────────
       BOTÓN AGREGAR AL CARRITO — con validación
       ───────────────────────────────────────────── */
    $("#btn-agregar").click(function(){
        // 1. Validar ANTES de enviar cualquier petición
        if (!validarFormulario()) {
            // Hay errores → desplazarse al primer campo con error y detener
            var $primerError = $(".spa-input-error").first();
            if ($primerError.length) {
                $('html, body').animate({
                    scrollTop: $primerError.offset().top - 120
                }, 300);
            }
            return; // ← DETIENE el proceso, no hace la petición AJAX
        }

        // 2. Sin errores → cargar carrito
        $("#carrito").load(
            '{{ url("cargacarritocitas") }}' + '?' + $("#form-cita").serialize()
        );
    });

});
</script>

<div class="spa-header">
    <div>
        <div class="spa-tag">Aura Spa Harmony</div>
        <h1>Agendar <em>Cita</em></h1>
    </div>
    <div style="margin-left:auto;">
        <a href="{{ route('reportecitas') }}" class="spa-btn spa-btn-outline" style="text-decoration:none; font-size:13px;">
            Ver reporte
        </a>
    </div>
</div>

<div class="spa-container">
<form id="form-cita">

    {{-- CLIENTE --}}
    <div class="spa-section">
        <div class="spa-section-label">Datos del Cliente</div>

        <div class="spa-grid cols-1" style="margin-bottom:16px;">
            <div class="spa-field">
                <label class="spa-label">Buscar cliente registrado</label>
                <select id="select-cliente" class="spa-select">
                    <option value="">— Nuevo cliente —</option>
                    @foreach($clientes as $cl)
                        <option value="{{ $cl->idc }}"
                            data-idc="{{ $cl->idc }}"
                            data-nombre="{{ $cl->nombre }}"
                            data-ap="{{ $cl->ap }}"
                            data-telefono="{{ $cl->telefono }}">
                            {{ $cl->nombre }} {{ $cl->ap }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="spa-grid">
            {{-- IDC (solo lectura) --}}
            <div class="spa-field">
                <label class="spa-label">IDC</label>
                <input type="text" name="idc" id="idc" class="spa-input" value="{{ $sigue }}" readonly>
            </div>

            {{-- TELÉFONO con validación --}}
            <div class="spa-field">
                <label class="spa-label">Teléfono <span style="color:var(--error);">*</span></label>
                <input type="tel"
                       name="telefono"
                       id="telefono"
                       class="spa-input"
                       maxlength="10"
                       pattern="\d{10}"
                       placeholder="10 dígitos"
                       data-validar="true"
                       autocomplete="tel">
                <span id="err-telefono" class="spa-error-msg spa-hidden"></span>
            </div>

            {{-- NOMBRE con validación --}}
            <div class="spa-field">
                <label class="spa-label">Nombre <span style="color:var(--error);">*</span></label>
                <input type="text"
                       name="nombre"
                       id="nombre"
                       class="spa-input"
                       pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                       placeholder="Solo letras"
                       data-validar="true"
                       autocomplete="given-name">
                <span id="err-nombre" class="spa-error-msg spa-hidden"></span>
            </div>

            {{-- APELLIDO con validación --}}
            <div class="spa-field">
                <label class="spa-label">Apellido Paterno <span style="color:var(--error);">*</span></label>
                <input type="text"
                       name="ap"
                       id="ap"
                       class="spa-input"
                       pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                       placeholder="Solo letras"
                       data-validar="true"
                       autocomplete="family-name">
                <span id="err-ap" class="spa-error-msg spa-hidden"></span>
            </div>
        </div>
    </div>

    {{-- CITA --}}
    <div class="spa-section">
        <div class="spa-section-label">Detalles de la Cita</div>
        <div class="spa-grid cols-3">
            <div class="spa-field">
                <label class="spa-label">Género</label>
                <select name="idtc" id="idtc" class="spa-select">
                    <option value="">— Seleccionar —</option>
                    @foreach($tiposcliente as $t)
                        <option value="{{ $t->idtc }}">{{ $t->tipo_cliente }}</option>
                    @endforeach
                </select>
            </div>
            <div class="spa-field">
                <label class="spa-label">Fecha</label>
                <input type="date" name="fecha" class="spa-input">
            </div>
            <div class="spa-field">
                <label class="spa-label">Hora</label>
                <input type="time" name="hora" class="spa-input">
            </div>
        </div>
    </div>

    {{-- HOMBRE --}}
    <div id="bloque-hombre" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicio — Hombre</div>
        <div class="spa-grid">
            <div class="spa-field">
                <label class="spa-label">Largo del Cabello</label>
                <select name="idlch" class="spa-select">
                    <option value="">— Seleccionar —</option>
                    @foreach($largosh as $l)
                        <option value="{{ $l->idlcm }}">{{ $l->largo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="spa-field">
                <label class="spa-label">Tipo de Corte</label>
                <select name="idtch" class="spa-select">
                    <option value="">— Seleccionar —</option>
                    @foreach($cortesh as $c)
                        <option value="{{ $c->idtch }}">{{ $c->corte }}</option>
                    @endforeach
                </select>
            </div>
            <div class="spa-field">
                <label class="spa-label">Estilo</label>
                <select name="idec" class="spa-select">
                    <option value="">— Seleccionar —</option>
                    @foreach($estilos as $e)
                        <option value="{{ $e->idec }}">{{ $e->estilo }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- MUJER --}}
    <div id="bloque-mujer" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicio — Mujer</div>
        <div class="spa-grid">
            <div class="spa-field">
                <label class="spa-label">Largo del Cabello</label>
                <select name="idlcm" class="spa-select">
                    <option value="">— Seleccionar —</option>
                    @foreach($largosm as $l)
                        <option value="{{ $l->idlcm }}">{{ $l->largo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="spa-field">
                <label class="spa-label">Tipo de Corte</label>
                <select name="idtcm" class="spa-select">
                    <option value="">— Seleccionar —</option>
                    @foreach($cortesm as $c)
                        <option value="{{ $c->idtch }}">{{ $c->corte }}</option>
                    @endforeach
                </select>
            </div>
            <div class="spa-field">
                <label class="spa-label">Flequillo</label>
                <select name="idf" class="spa-select">
                    <option value="">Sin flequillo</option>
                    @foreach($flequillos as $f)
                        <option value="{{ $f->idf }}">{{ $f->flequillo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="spa-field">
                <label class="spa-label">Estilo</label>
                <select name="idec" class="spa-select">
                    <option value="">— Seleccionar —</option>
                    @foreach($estilos as $e)
                        <option value="{{ $e->idec }}">{{ $e->estilo }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- SERVICIOS ADICIONALES HOMBRE --}}
    <div id="bloque-servicios-h" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicios Adicionales</div>
        <div class="spa-radio-group">
            @foreach($servicios as $s)
                @if($s->ids != 4)
                <label class="spa-radio-option">
                    <input type="radio" name="ids" value="{{ $s->ids }}">
                    {{ $s->servicio }}
                </label>
                @endif
            @endforeach
            <label class="spa-radio-option">
                <input type="radio" name="ids" value="" checked> Ninguno
            </label>
        </div>
    </div>

    {{-- SERVICIOS ADICIONALES MUJER --}}
    <div id="bloque-servicios-m" class="spa-section spa-hidden">
        <div class="spa-section-label">Servicios Adicionales</div>
        <div class="spa-radio-group">
            @foreach($servicios as $s)
                @if($s->ids != 3)
                <label class="spa-radio-option">
                    <input type="radio" name="ids" value="{{ $s->ids }}">
                    {{ $s->servicio }}
                </label>
                @endif
            @endforeach
            <label class="spa-radio-option">
                <input type="radio" name="ids" value="" checked> Ninguno
            </label>
        </div>
    </div>

    <div class="spa-actions">
        <button type="button" id="btn-agregar" class="spa-btn">+ Agregar al carrito</button>
    </div>

</form>

{{-- CARRITO --}}
<div style="margin-top:32px;">
    <div class="spa-section-label" style="margin-bottom:12px;">Carrito de servicios</div>
    <div id="carrito"></div>
</div>


</div>
@stop

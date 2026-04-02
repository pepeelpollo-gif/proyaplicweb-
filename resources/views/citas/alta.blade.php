@extends('principal')

@section('contenido')

{{-- Cargar el CSS del módulo --}}
<link rel="stylesheet" href="{{ asset('css/spa.css') }}">

<script type="text/javascript">
$(document).ready(function(){

    // Seleccionar cliente existente → rellenar campos
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
    });

    // Mostrar/ocultar bloques por género
    $("#idtc").change(function(){
        var g = this.value;
        $("#bloque-hombre, #bloque-mujer, #bloque-servicios-h, #bloque-servicios-m").addClass('spa-hidden');
        if(g == 1){
            $("#bloque-hombre, #bloque-servicios-h").removeClass('spa-hidden');
        } else if(g == 2){
            $("#bloque-mujer, #bloque-servicios-m").removeClass('spa-hidden');
        }
    });

    $("#btn-agregar").click(function(){
        $("#carrito").load(
            '{{ url("cargacarritocitas") }}' + '?' + $("#form-cita").serialize()
        );
    });

});
</script>

{{-- ENCABEZADO --}}
<div class="spa-header">
    <div>
        <div class="spa-tag">Aura Spa Harmony</div>
        <h1>Agendar <em>Cita</em></h1>
    </div>
</div>

<div class="spa-container">
<form id="form-cita">

    {{-- SECCIÓN: Datos del Cliente --}}
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
            <div class="spa-field">
                <label class="spa-label">IDC</label>
                <input type="text" name="idc" id="idc" class="spa-input" value="{{ $sigue }}" readonly>
            </div>
            <div class="spa-field">
                <label class="spa-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="spa-input" maxlength="15">
            </div>
            <div class="spa-field">
                <label class="spa-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="spa-input">
            </div>
            <div class="spa-field">
                <label class="spa-label">Apellido Paterno</label>
                <input type="text" name="ap" id="ap" class="spa-input">
            </div>
        </div>
    </div>

    {{-- SECCIÓN: Detalles de la Cita --}}
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

    {{-- SECCIÓN: Servicio (Hombre) --}}
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

    {{-- SECCIÓN: Servicio (Mujer) --}}
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

    {{-- SECCIÓN: Servicios Adicionales (Hombre) --}}
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
                <input type="radio" name="ids" value="" checked>
                Ninguno
            </label>
        </div>
    </div>

    {{-- SECCIÓN: Servicios Adicionales (Mujer) --}}
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
                <input type="radio" name="ids" value="" checked>
                Ninguno
            </label>
        </div>
    </div>

    {{-- BOTÓN --}}
    <div class="spa-actions">
        <button type="button" id="btn-agregar" class="spa-btn">
            + Agregar al carrito
        </button>
    </div>

</form>

{{-- CARRITO --}}
<div style="margin-top:32px;">
    <div class="spa-section-label" style="margin-bottom:12px;">Carrito de servicios</div>
    <div id="carrito"></div>
</div>

</div>{{-- fin spa-container --}}

@stop

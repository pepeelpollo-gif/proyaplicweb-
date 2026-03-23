@extends('principal')

@section('contenido')

<script type="text/javascript">
$(document).ready(function(){

    // ── Mostrar/ocultar campos según género ───────────────────
    $("#idtc").change(function(){
        var genero = this.value;
        if(genero == 1){
            // Hombre
            $("#bloque-hombre").show();
            $("#bloque-mujer").hide();
            $("#bloque-servicios-h").show();
            $("#bloque-servicios-m").hide();
        } else if(genero == 2){
            // Mujer
            $("#bloque-hombre").hide();
            $("#bloque-mujer").show();
            $("#bloque-servicios-h").hide();
            $("#bloque-servicios-m").show();
        } else {
            $("#bloque-hombre").hide();
            $("#bloque-mujer").hide();
            $("#bloque-servicios-h").hide();
            $("#bloque-servicios-m").hide();
        }
    });

    // ── Botón Agregar al Carrito (AJAX igual que actividades) ─
    $("#btn-agregar").click(function(){
        $("#carrito").load(
            '{{ url("cargacarrito") }}' + '?' + $(this).closest('form').serialize()
        );
    });

});
</script>

<h1>Agendar Cita</h1>
<br>

<form id="form-cita">

    {{-- ── IDENTIFICACIÓN ─────────────────────────────────────── --}}
    <table>
        <tr>
            <td><label>IDC (Cliente)</label></td>
            <td><input type="text" name="idc" id="idc" value="{{ $sigue }}" readonly></td>
        </tr>
    </table>

    <hr>

    {{-- ── DATOS DEL CLIENTE ──────────────────────────────────── --}}
    <h3>Datos del Cliente</h3>
    <table>
        <tr>
            <td><label>Nombre</label></td>
            <td><input type="text" name="nombre" id="nombre" placeholder="Ej. Claudia"></td>
        </tr>
        <tr>
            <td><label>Apellido Paterno</label></td>
            <td><input type="text" name="ap" id="ap" placeholder="Ej. López"></td>
        </tr>
        <tr>
            <td><label>Teléfono</label></td>
            <td><input type="text" name="telefono" id="telefono" placeholder="10 dígitos" maxlength="15"></td>
        </tr>
        <tr>
            <td><label>Género</label></td>
            <td>
                <select name="idtc" id="idtc">
                    <option value="">-- Seleccionar --</option>
                    @foreach($tiposcliente as $t)
                        <option value="{{ $t->idtc }}">{{ $t->tipo_cliente }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Fecha de Cita</label></td>
            <td><input type="date" name="fecha" id="fecha"></td>
        </tr>
        <tr>
            <td><label>Hora de Inicio</label></td>
            <td><input type="time" name="hora" id="hora"></td>
        </tr>
    </table>

    {{-- IDs ocultos que se generan automáticamente --}}
    <input type="hidden" name="idac"  value="1">
    <input type="hidden" name="idfhc" value="1">

    <hr>

    {{-- ── SERVICIO DE CABELLO ─────────────────────────────────── --}}
    <h3>Servicio de Cabello</h3>

    {{-- Campos para HOMBRE --}}
    <div id="bloque-hombre" style="display:none;">
        <table>
            <tr>
                <td><label>Largo del Cabello</label></td>
                <td>
                    <select name="idlch" id="idlch">
                        <option value="">-- Seleccionar --</option>
                        @foreach($largosh as $l)
                            <option value="{{ $l->idlch }}">{{ $l->largo }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Tipo de Corte</label></td>
                <td>
                    <select name="idtch" id="idtch">
                        <option value="">-- Seleccionar --</option>
                        @foreach($cortesh as $c)
                            <option value="{{ $c->idtch }}">{{ $c->corte }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
    </div>

    {{-- Campos para MUJER --}}
    <div id="bloque-mujer" style="display:none;">
        <table>
            <tr>
                <td><label>Largo del Cabello</label></td>
                <td>
                    <select name="idlcm" id="idlcm">
                        <option value="">-- Seleccionar --</option>
                        @foreach($largosm as $l)
                            <option value="{{ $l->idlcm }}">{{ $l->largo }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Tipo de Corte</label></td>
                <td>
                    <select name="idtcm" id="idtcm">
                        <option value="">-- Seleccionar --</option>
                        @foreach($cortesm as $c)
                            <option value="{{ $c->idtcm }}">{{ $c->corte }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Flequillo</label></td>
                <td>
                    <select name="idf" id="idf">
                        <option value="">Sin flequillo</option>
                        @foreach($flequillos as $f)
                            <option value="{{ $f->idf }}">{{ $f->flequillo }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
    </div>

    {{-- Estilo (común para ambos géneros) --}}
    <table>
        <tr>
            <td><label>Estilo de Cabello</label></td>
            <td>
                <select name="idec" id="idec">
                    <option value="">-- Seleccionar --</option>
                    @foreach($estilos as $e)
                        <option value="{{ $e->idec }}">{{ $e->estilo }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    </table>

    <hr>

    {{-- ── SERVICIOS ADICIONALES ──────────────────────────────── --}}
    <h3>Servicios Adicionales</h3>

    {{-- Servicios Hombre --}}
    <div id="bloque-servicios-h" style="display:none;">
        <table>
            @foreach($servicios as $s)
                @if($s->ids != 4) {{-- excluye Depilación --}}
                <tr>
                    <td>
                        <label>
                            <input type="radio" name="ids" value="{{ $s->ids }}">
                            {{ $s->servicio }}
                        </label>
                    </td>
                </tr>
                @endif
            @endforeach
            <tr><td><label><input type="radio" name="ids" value=""> Ninguno</label></td></tr>
        </table>
    </div>

    {{-- Servicios Mujer --}}
    <div id="bloque-servicios-m" style="display:none;">
        <table>
            @foreach($servicios as $s)
                @if($s->ids != 3) {{-- excluye Afeitado --}}
                <tr>
                    <td>
                        <label>
                            <input type="radio" name="ids" value="{{ $s->ids }}">
                            {{ $s->servicio }}
                        </label>
                    </td>
                </tr>
                @endif
            @endforeach
            <tr><td><label><input type="radio" name="ids" value=""> Ninguno</label></td></tr>
        </table>
    </div>

    <br>
    <input type="button" id="btn-agregar" value="Agregar al Carrito">

</form>

<hr>

{{-- ── CARRITO (se carga vía AJAX) ───────────────────────────── --}}
<h2>Carrito</h2>
<div id="carrito"></div>

@stop

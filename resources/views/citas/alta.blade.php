@extends('principal')

@section('contenido')

<script type="text/javascript">
$(document).ready(function(){

    $("#idtc").change(function(){
        var genero = this.value;
        $("#bloque-hombre, #bloque-mujer").hide();
        $("#bloque-servicios-h, #bloque-servicios-m").hide();
        if(genero == 1){
            $("#bloque-hombre").show();
            $("#bloque-servicios-h").show();
        } else if(genero == 2){
            $("#bloque-mujer").show();
            $("#bloque-servicios-m").show();
        }
    });

    $("#btn-agregar").click(function(){
        $("#carrito").load(
            '{{ url("cargacarritocitas") }}' + '?' + $("#form-cita").serialize()
        );
    });

});
</script>

<h1>Agendar Cita</h1>
<br>

<form id="form-cita">

    <h3>Datos del Cliente</h3>
    <table>
        <tr>
            <td><label>IDC</label></td>
            <td><input type="text" name="idc" value="{{ $sigue }}" readonly></td>
        </tr>
        <tr>
            <td><label>Nombre</label></td>
            <td><input type="text" name="nombre"></td>
        </tr>
        <tr>
            <td><label>Apellido Paterno</label></td>
            <td><input type="text" name="ap"></td>
        </tr>
        <tr>
            <td><label>Teléfono</label></td>
            <td><input type="text" name="telefono" maxlength="15"></td>
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
            <td><input type="date" name="fecha"></td>
        </tr>
        <tr>
            <td><label>Hora de Inicio</label></td>
            <td><input type="time" name="hora"></td>
        </tr>
    </table>

    <hr>
    <h3>Servicio de Cabello</h3>

    <div id="bloque-hombre" style="display:none;">
        <table>
            <tr>
                <td><label>Largo del Cabello</label></td>
                <td>
                    <select name="idlch">
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
                    <select name="idtch">
                        <option value="">-- Seleccionar --</option>
                        @foreach($cortesh as $c)
                            <option value="{{ $c->idtch }}">{{ $c->corte }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
    </div>

    <div id="bloque-mujer" style="display:none;">
        <table>
            <tr>
                <td><label>Largo del Cabello</label></td>
                <td>
                    <select name="idlcm">
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
                    <select name="idtcm">
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
                    <select name="idf">
                        <option value="">Sin flequillo</option>
                        @foreach($flequillos as $f)
                            <option value="{{ $f->idf }}">{{ $f->flequillo }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <td><label>Estilo de Cabello</label></td>
            <td>
                <select name="idec">
                    <option value="">-- Seleccionar --</option>
                    @foreach($estilos as $e)
                        <option value="{{ $e->idec }}">{{ $e->estilo }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    </table>

    <hr>
    <h3>Servicios Adicionales</h3>

    <div id="bloque-servicios-h" style="display:none;">
        <table>
            @foreach($servicios as $s)
                @if($s->ids != 4)
                <tr><td><label><input type="radio" name="ids" value="{{ $s->ids }}"> {{ $s->servicio }}</label></td></tr>
                @endif
            @endforeach
            <tr><td><label><input type="radio" name="ids" value="" checked> Ninguno</label></td></tr>
        </table>
    </div>

    <div id="bloque-servicios-m" style="display:none;">
        <table>
            @foreach($servicios as $s)
                @if($s->ids != 3)
                <tr><td><label><input type="radio" name="ids" value="{{ $s->ids }}"> {{ $s->servicio }}</label></td></tr>
                @endif
            @endforeach
            <tr><td><label><input type="radio" name="ids" value="" checked> Ninguno</label></td></tr>
        </table>
    </div>

    <br>
    <input type="button" id="btn-agregar" value="Agregar al Carrito">

</form>

<hr>
<h2>Carrito</h2>
<div id="carrito"></div>

@stop
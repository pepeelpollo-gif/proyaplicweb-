@extends('principal')

@section('contenido')

<link rel="stylesheet" href="{{ asset('css/spa.css') }}">

<script>
$(document).ready(function() {

    function refrescarClientes() {
        $.ajax({
            url: '{{ route("getlistaclientes") }}',
            type: 'GET',
            success: function(clientes) {
                var select = $('#select-cliente');
                var valorActual = select.val(); 
                
                select.empty(); 
                select.append('<option value="">— Nuevo cliente —</option>');
                
                $.each(clientes, function(i, cliente) {
                    select.append(`<option value="${cliente.idc}" 
                        data-idc="${cliente.idc}" 
                        data-nombre="${cliente.nombre}" 
                        data-ap="${cliente.ap}" 
                        data-telefono="${cliente.telefono}">
                        ${cliente.nombre} ${cliente.ap}
                    </option>`);
                });
                
                select.val(valorActual);
            }
        });
    }

    function mostrarGenero(g) {
        $('#bloque-hombre, #bloque-mujer, #bloque-servicios-h, #bloque-servicios-m').addClass('spa-hidden');
        
        $('select[name="idlch"], select[name="idtch"], select[name="idech"]').prop('required', false);
        $('select[name="idlcm"], select[name="idtcm"], select[name="idecm"]').prop('required', false);

        if (g == 1) {
            $('#bloque-hombre, #bloque-servicios-h').removeClass('spa-hidden');
            $('select[name="idlch"], select[name="idtch"], select[name="idech"]').prop('required', true);
            
        } else if (g == 2) {
            $('#bloque-mujer, #bloque-servicios-m').removeClass('spa-hidden');
            $('select[name="idlcm"], select[name="idtcm"], select[name="idecm"]').prop('required', true);
        }
    }

    $('#idtc').change(function() {
        mostrarGenero(this.value);
    });

    $('#btn-agregar').click(function(e) {
        e.preventDefault(); 
        
        var form = $('#form-cita')[0];
        if (!form.checkValidity()) {
            form.reportValidity(); 
            return; 
        }

        var datosDelFormulario = $('#form-cita').serialize();

        $.ajax({
            url: '{{ route("cargacarritocitas") }}',
            type: 'GET',
            data: datosDelFormulario,
            success: function(respuestaHTML) {
                $('#carrito').html(respuestaHTML);
                setTimeout(function() {
                    document.getElementById('carrito').scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);

                $('select[name="idlch"], select[name="idtch"], select[name="idech"]').val('');
                $('select[name="idlcm"], select[name="idtcm"], select[name="idecm"], select[name="idf"]').val('');
                $('input[name="idsh"][value=""], input[name="idsm"][value=""]').prop('checked', true);
            },
            error: function(xhr, status, error) {
                console.error("Error en AJAX: ", error);
                alert("Hubo un problema al agregar el servicio. Abre la consola (F12) para más detalles.");
            }
        });
    });

});
</script>

<div class="spa-header">
    <div>
        <div class="spa-tag">Aura Spa Harmony</div>
        <h1>Agendar <em>Cita</em></h1>
    </div>
    <div style="margin-left:auto;">
        <a href="{{ route('reportecitas') }}" class="spa-btn spa-btn-outline">Ver reporte</a>
    </div>
</div>

<div class="spa-container">
<form id="form-cita">

    <input type="hidden" name="idac_actual" id="idac_actual" value="">

    <div class="spa-section">
        <div class="spa-section-label">Datos del Cliente</div>
        <div class="spa-grid cols-1" style="margin-bottom:16px;">
            <div class="spa-field">
                <label class="spa-label">Buscar cliente registrado</label>
                <select id="select-cliente" class="spa-select">
                    <option value="">— Nuevo cliente —</option>
                    @foreach($clientes as $cl)
                    <option value="{{ $cl->idc }}" data-idc="{{ $cl->idc }}" data-nombre="{{ $cl->nombre }}" data-ap="{{ $cl->ap }}" data-telefono="{{ $cl->telefono }}">
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
            
                <input type="text" name="telefono" id="telefono" class="spa-input" 
                       minlength="10" maxlength="15" 
                       pattern="[0-9]+" 
                       title="Debe contener entre 10 y 15 números" 
                       oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                       required>
            </div>
            <div class="spa-field">
                <label class="spa-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="spa-input" 
                       minlength="3" maxlength="30" 
                       pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" 
                       title="Solo se aceptan letras (entre 3 y 30 caracteres)" 
                       oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');" 
                       required>
            </div>
            <div class="spa-field">
                <label class="spa-label">Apellido Paterno</label>
                <input type="text" name="ap" id="ap" class="spa-input" 
                       minlength="3" maxlength="30" 
                       pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" 
                       title="Solo se aceptan letras (entre 3 y 30 caracteres)" 
                       oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');" 
                       required>
            </div>
        </div>
    </div>

    <div class="spa-section">
        <div class="spa-section-label">Detalles de la Cita</div>
        <div class="spa-grid cols-3">
            <div class="spa-field">
                <label class="spa-label">Género</label>
                <select name="idtc" id="idtc" class="spa-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($tiposcliente as $t)
                    <option value="{{ $t->idtc }}">{{ $t->tipo_cliente }}</option>
                    @endforeach
                </select>
            </div>
            <div class="spa-field">
                <label class="spa-label">Fecha</label>
                <input type="date" name="fecha" class="spa-input" required>
            </div>
            <div class="spa-field">
                <label class="spa-label">Hora</label>
                <input type="time" name="hora" class="spa-input" required>
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
                <select name="idech" class="spa-select">
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
                <select name="idecm" class="spa-select">
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
                    <input type="radio" name="idsh" value="{{ $s->ids }}">
                    {{ $s->servicio }}
                </label>
                @endif
            @endforeach
            <label class="spa-radio-option">
                <input type="radio" name="idsh" value="" checked> Ninguno
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
                    <input type="radio" name="idsm" value="{{ $s->ids }}">
                    {{ $s->servicio }}
                </label>
                @endif
            @endforeach
            <label class="spa-radio-option">
                <input type="radio" name="idsm" value="" checked> Ninguno
            </label>
        </div>
    </div>

    <div class="spa-actions" style="display:flex; gap:12px;">
        <button type="button" id="btn-agregar" class="spa-btn">+ Agregar al carrito</button>
        <a href="{{ route('altacita') }}" class="spa-btn spa-btn-outline" id="btn-nueva-cita" style="display:none;">Terminar y agendar otra cita distinta</a>
    </div>

</form>

<div style="margin-top:32px;">
    <div class="spa-section-label" style="margin-bottom:12px;">Carrito de servicios</div>
    <div id="carrito"></div>
</div>

</div>
@stop
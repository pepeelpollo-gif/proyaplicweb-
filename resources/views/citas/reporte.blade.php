@extends('principal')

@section('contenido')

<link rel="stylesheet" href="{{ asset('css/spa.css') }}">

{{-- ENCABEZADO --}}
<div class="spa-header">
    <div>
        <div class="spa-tag">Aura Spa Harmony</div>
        <h1>Reporte <em>General</em></h1>
    </div>
    <div style="margin-left:auto;">
        <a href="{{ route('altacita') }}" class="spa-btn spa-btn-outline" style="text-decoration:none; font-size:13px;">
            + Nueva cita
        </a>
    </div>
</div>

<div class="spa-container">

    <div class="spa-section" style="padding:0; overflow:hidden;">
        <table class="spa-reporte-table">
            <thead>
                <tr>
                    <th>IDC</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Género</th>
                    <th>Servicios</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reporte as $r)
                <tr>
                    <td style="color:var(--gris-med); font-size:12px;">#{{ $r->idc }}</td>
                    <td style="font-weight:500;">{{ $r->nombre }}</td>
                    <td>{{ $r->ap }}</td>
                    <td style="font-family:monospace; font-size:13px;">{{ $r->telefono }}</td>
                    <td>{{ $r->fecha }}</td>
                    <td style="color:var(--gris-med);">{{ $r->hora }}</td>
                    <td>
                        @if($r->genero == 'Hombre')
                            <span class="spa-badge spa-badge-h">{{ $r->genero }}</span>
                        @else
                            <span class="spa-badge spa-badge-m">{{ $r->genero }}</span>
                        @endif
                    </td>
                    <td class="spa-num">{{ $r->num_servicios }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:32px; color:var(--gris-med); font-style:italic;">
                        No hay citas registradas aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pie de tabla --}}
    <div style="display:flex; justify-content:space-between; align-items:center; padding-top:12px;">
        <span style="font-size:12px; color:var(--gris-med);">
            {{ count($reporte) }} cita{{ count($reporte) != 1 ? 's' : '' }} registrada{{ count($reporte) != 1 ? 's' : '' }}
        </span>
    </div>

</div>

@stop

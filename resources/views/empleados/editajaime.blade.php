@extends('principal')

@section('contenido')
<center>
    <h1>Editar Animal</h1>
    <br>
    <form action="#" method="post" enctype="multipart/form-data">
        
        <table>
            <tr>
                <td>ID:</td>
                <td><input type="text" name="ida" value="{{ $animal->ida }}" readonly></td>
            </tr>
            <tr>
                <td>Nombre:</td>
                <td><input type="text" name="nombre" value="{{ $animal->nombre }}"></td>
            </tr>
            <tr>
                <td>Especie:</td>
                <td>
                    <select name="ides">
                        <option value="{{ $animal->ides }}">{{$animal->especie}}</option>
                        @foreach($especies as $esp)
                            <option value="{{ $esp->ides }}">{{ $esp->nombre }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>Foto:</td>
                <td>
                    <img src="{{ asset('imagenes/' . $animal->foto) }}" height="100">
                </td>
            </tr>
        </table>
        
        <br>
        <button type="button">Guardar Cambios </button>
    </form>
</center>
@stop
@extends('principal')

@section('contenido')
        <h1>Alta de empleados</h1>
        <br>
        <form action ="{{route('guardaempleado')}}" method="post" enctype='multipart/form-data'>
            {{csrf_field()}}
            <table >
               <tr>
                    <td>Clave</td>
                    <td>
                        @if($errors->has('ide'))
                            {{ $errors->first('ide') }}
                        @endif
                        <input type="text" name="idemp" value="{{ $idsigue }}"readonly>
                    </td>
                </tr>
                <tr>
                    <td>Nombre</td>
                    <td>
                        @if($errors->has('nombre'))
                            {{ $errors->first('nombre') }}
                        @endif
                        <input type="text" name="nombre" value="{{ old('nombre') }}">
                    </td>
                </tr>
                <tr>
                    <td>Apellido</td>
                    <td>
                        @if($errors->has('apellido'))
                            {{ $errors->first('apellido') }}
                        @endif
                        <input type="text" name="apellido" value="{{ old('apellido') }}">
                    </td>
                </tr>
                <tr>
                    <td>Edad</td>
                    <td>
                        @if($errors->has('edad'))
                            {{ $errors->first('edad') }}
                        @endif
                        <input type="number" name="edad" value="{{ old('edad') }}" min="18" max="65">
                    </td>
                </tr>
                <tr>
                    <td>Fecha nacimiento</td>
                    <td><input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>
                        @if($errors->has('email'))
                            {{ $errors->first('email') }}
                        @endif
                        <input type="email" name="email" value="{{ old('email') }}" >
                    </td>
                </tr>
                <tr>
                    <td>RFC</td>
                    <td>
                     @if($errors->has('rfc'))
                            {{ $errors->first('rfc') }}
                        @endif
                    <input type="text" name="rfc" value="{{ old('rfc') }}"></td>
                </tr>
                <tr>
                    <td>Selecciona carrera</td>
                    <td>
                        <select name='idca' class="form-select">
                            @foreach($carreras as $c)
                            <option value = '{{$c->idca}}'>{{$c->nombre}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Sexo</td>
                    <td>
                        <input type="radio" name="sexo" value="M">Masculino
                        <input type="radio" name="sexo" value="F">Femenino
                    </td>
                </tr>
                
                <tr>
                    <td>Curriculum</td>
                    <td><textarea name="curriculom">{{ old('curriculom') }}</textarea></td>
                </tr>
                
                <tr>
                    <td>foto</td>
                    <td>
                     @if($errors->has('foto'))
                            {{ $errors->first('foto') }}
                        @endif
                    <input type="file" name="foto" value="{{ old('foto') }}"></td>
                </tr>
                <tr>
                    <td>Activo</td>
                    <td><input type = 'radio' name = 'activo' value = 'Si' checked>Si
                    <td><input type = 'radio' name = 'activo' value = 'No'>No
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" class="lead" _msttexthash="3134703" _msthash="200" value="Guardar">
                        
                    </td>
                </tr>
            </table>
        </form>
@stop
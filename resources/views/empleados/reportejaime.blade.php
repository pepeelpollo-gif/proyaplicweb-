<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Animales</title>
    <style>
        table { width: 50%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; text-align: center; }
        th { background-color: #f2f2f2; }
        img { width: 100px; height: auto; border-radius: 5px; }
        .btn-eliminar { background-color: #ff4d4d; color: white; border: none; padding: 5px 10px;}
    </style>
</head>
<body>
    <h1>Reporte de Animales</h1>
    
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Especie</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($animales as $animal)
            <tr>
                <td>
                    <img src="{{ asset('imagenes/' . $animal->foto) }}" alt="{{ $animal->nombre }}">
                </td>
                <td>{{ $animal->nombre }}</td>
                <td>
                    {{ $animal->especie ? $animal->especie->nombre : 'Sin asignar' }}
                </td>
                            <td>
                <a href="{{ route('edita.jaime', $animal->ida) }}">
                    <button type="button">
                        Editar
                    </button>
                </a>

                <form action="{{ route('animal.destroy', $animal->ida) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-eliminar" onclick="return confirm('¿Eliminar?')">
                        Eliminar
                    </button>
                </form>
            </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No hay animales registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>



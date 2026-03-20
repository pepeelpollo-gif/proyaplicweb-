<?php

namespace App\Http\Controllers;

use App\Models\animales;
use App\Models\especies; 
use Illuminate\Http\Request;

class examenjaimecontroller extends Controller
{
    public function index()
    {
        $animales = animales::with('especie')->get();
        return view('empleados.reportejaime', compact('animales'));
    }

    public function destroy($id)
    {
        $animal = animales::find($id);
        if ($animal) {
            $animal->delete();
            return redirect()->route('reporte.jaime')->with('success', 'Animal eliminado correctamente.');
        }
        return redirect()->route('reporte.jaime')->with('error', 'No se pudo encontrar el animal.');
    }

    public function edit($id)
    {
        
        $animal_data = \DB::select("SELECT a.ida, a.nombre, a.ides, a.foto, e.nombre AS especie 
                                    FROM animales AS a 
                                    INNER JOIN especies AS e ON e.ides = a.ides 
                                    WHERE a.ida = ? LIMIT 1", [$id]);

        if (count($animal_data) > 0) {
            $animal = $animal_data[0]; 
        } else {
            return redirect()->route('reporte.jaime')->with('error', 'Animal no encontrado');
        }
        $especies = especies::orderBy('nombre','ASC')->get();

        return view('empleados.editajaime', compact('animal', 'especies'));
    }
}
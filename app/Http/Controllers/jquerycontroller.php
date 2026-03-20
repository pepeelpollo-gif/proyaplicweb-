<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class jquerycontroller extends Controller
{
    public function p1(){
       
    return view('jquery.practica1');
    }
    public function calculo(request $request){
        $b = $request->base;
        $a = $request->altura;
        $total = ($b * $a) /2;

        return view('jquery.resprac1')
        ->with('b',$b)
        ->with('a',$a)
        ->with('total',$total);
    }

    public function p2(){
        $empleados = \DB::select("SELECT idemp,CONCAT  
        (nombre, ' ',apellido) 
        AS nombre
        FROM empleados");

        return view ('jquery.practica2')-> with('empleados', $empleados);
    }

    public function datos(request $request){
        $ide= $request->ide;
        $empleado = \DB::select("SELECT rfc FROM empleados
        where idemp= $ide");

        echo "el ide del empleado seleccionado es: " . $ide. "su rfc es: " . $empleado[0]->rfc ;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class accesocontroller extends Controller
{
    public function login(){
        return view('acceso.login');
    }

    public function validar(request $request){
        $acceso = \DB::select("SELECT idu,nombre, apellido, tipo, activo
FROM usuarios
WHERE correo='$request->correo' AND pasw= MD5 ('$request->password')
AND activo= 'Si'");

    $cuantos=count($acceso);
    if($cuantos==0){
        Session::flash('mensaje', "Password o correo incorrectos gay");
    return  redirect()->route('login');

    } else {
        Session::put ('sesionnombre', $acceso[0]-> nombre . $acceso[0]->apellido);
        Session::put ('sesionidu', $acceso[0]->idu);
        Session::put ('sesiontipo', $acceso[0]->tipo);
        return redirect()-> route('inicio');
    }
}

    public function cerrarsesion(){
        Session::forget('sesionnombre');
        Session::forget('sesionidu');
        Session::forget('sesiontipo');
        Session::flush();
        Session::flash('mensaje','Sesion cerrada correctamente gay');
        return redirect()->route('login');

    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\empleados;
use App\Models\carreras;

use Session;

class empleadoscontroller extends Controller

{

    public function inicio(){
        if(Session::get('sesionidu')){
            return view ('principal');
        } else{
            Session::flash('mensaje', "Debes iniciar sesión gay");
            return redirect()->route('login');
        }
    }

    public function reporteempleados(){
        if(Session::get('sesionidu')){

        $empleados = \DB::select("SELECT e.idemp,CONCAT(e.nombre,' ',e.apellido) AS nomc, e.correo,c.nombre AS carre,e.rfc, e.activo, e.foto 
        FROM empleados AS e INNER JOIN carreras AS c ON c.idca = e.idca ORDER BY e.nombre ASC");
        
        return view ('empleados.reporte')->with('empleados',$empleados);
        } else{
            Session::flash('mensaje', "Debes iniciar sesión gay");
            return redirect()->route('login');
        }
    }

    public function altaempleado(){

        if(Session::get('sesionidu')){

        $empleados = \DB :: select("SELECT idemp FROM empleados ORDER BY idemp DESC LIMIT 1");

        $idsigue = $empleados[0] -> idemp+1;

        $carreras = carreras::where('activo','=','si')->orderby('nombre','ASC')->get();
        
        return view ('empleados.alta')
        ->with('carreras',$carreras)
        ->with('idsigue',$idsigue);

        } else{
            Session::flash('mensaje', "Debes iniciar sesión gay");
            return redirect()->route('login');
        }
    }           
    public function guardaempleado(request $request)
    {
        $this->validate($request, [
        'idemp' => 'required|integer',
        'nombre' => 'required|alpha|regex:/^[A-Z][A-Z,a-z, ,é,ó,ñ,Á,Ó]+$/',
        'apellido' => 'required|alpha',
        'edad' => 'required|integer|min:18|max:100',
        'email'=> 'required|email',
        'rfc' => 'required|regex:/^[A-Z]{3,4}[0-9]{6}[A-Z,0-9]{3}$/',
        'foto'=>'mimes:jpg,png,jpeg',
        //  Expresiones irregulares

    ]);

    $file = $request->file('foto');
		if ($file != '')
		{
		$img = $request->idemp. $file->getClientOriginalName();
		\Storage::disk('local')->put($img, \File::get($file));
		}

        else{
            $img= 'sinfoto.jpg';
        }


    $empleados = new empleados;
    $empleados -> idemp = $request -> idemp;
    $empleados -> nombre = $request -> nombre;
    $empleados -> apellido = $request -> apellido;
    $empleados -> edad = $request -> edad;
    $empleados -> fechanac= $request -> fecha_nacimiento;
    $empleados -> correo= $request -> email;
    $empleados -> rfc = $request -> rfc;
    $empleados -> idca = $request -> idca;
    $empleados -> sexo = $request -> sexo;
    $empleados -> curriculom = $request -> curriculom;
    $empleados -> foto = $img;
    $empleados -> activo = $request -> activo;
    $empleados -> save();

    Session::flash('mensaje', "El empleado $request->nombre ha sido dado de alta");
    return  redirect()->route('reporteempleados');

    }
    public function desactivaempleado(request $request){

        $empleados = \DB::update("UPDATE empleados
SET activo ='No'
WHERE idemp = $request->idemp");

        Session::flash('mensaje', "El empleado $request->nombre ha sido desactivado");
    return  redirect()->route('reporteempleados');
    }

    public function activaempleado(request $request){

        $empleados = \DB::update("UPDATE empleados
SET activo ='Si'
WHERE idemp = $request->idemp");

        Session::flash('mensaje', "El empleado $request->nombre ha sido activado");
    return  redirect()->route('reporteempleados');
    }

    public function eliminaempleado(request $request){

        $empleados = \DB::update("DELETE FROM empleados
                                    WHERE idemp = $request->idemp");

        Session::flash('mensaje', "El empleado $request->nombre ha sido eliminado");
    return  redirect()->route('reporteempleados');
    }


public function editaempleado(request $request){

    $infoempleado= \DB::select("SELECT e.idemp, e.nombre AS empe, e.apellido, e.edad, e.correo, 
    e.fechanac, e.rfc, e.sexo, e.curriculom, e.idca,e.activo, c.nombre AS carre, e.foto
FROM empleados AS e
INNER JOIN carreras AS c ON c.idca = e.idca
WHERE idemp=$request->idemp");

$carreras = carreras::where('activo','=','si')
->orderby('nombre','ASC')->get();

    return view ('empleados.editaemp')
    ->with('carreras',$carreras)
    ->with('infoempleado', $infoempleado[0]);

}

public function actualizaemp(request $request)
    {

        $this->validate($request, [
        'idemp' => 'required|integer',
        'nombre' => 'required|alpha|regex:/^[A-Z][A-Z,a-z, ,é,ó,ñ,Á,Ó]+$/',
        'apellido' => 'required|alpha',
        'edad' => 'required|integer|min:18|max:100',
        'email'=> 'required|email',
        'rfc' => 'required|regex:/^[A-Z]{3,4}[0-9]{6}[A-Z,0-9]{3}$/',
        'foto'=> 'mimes:jpg,png,jpeg'
        //  Expresiones irregulares

    ]);

    $file = $request->file('foto');
		if ($file != '')
		{
		$img = $request->idemp. $file->getClientOriginalName();
		\Storage::disk('local')->put($img, \File::get($file));
		}
    

    $empleados = empleados::find($request->idemp);
    $empleados -> idemp = $request -> idemp;
    $empleados -> nombre = $request -> nombre;
    $empleados -> apellido = $request -> apellido;
    $empleados -> edad = $request -> edad;
    $empleados -> fechanac = $request -> fechanac;
    $empleados -> rfc = $request -> rfc;
    $empleados -> idca = $request -> idca;
    if ($file != '')
        {$empleados->foto = $img; }
    $empleados -> sexo = $request -> sexo;
    $empleados -> curriculom = $request -> curriculom;
    $empleados -> activo = $request -> activo;
    $empleados -> save();


    Session::flash('mensaje', "El empleado $request->nombre ha sido actualizado");
    return  redirect()->route('reporteempleados');

    }

}
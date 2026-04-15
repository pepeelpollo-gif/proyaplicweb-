<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\actividades;
use App\Models\actividadesdetalles;
use Illuminate\Support\Facades\DB;


use Session;

class actividadescontroller extends Controller
{
    public function cargacarrito(request $request)
    {
       // return $request;
        $existe = DB:: select ("SELECT COUNT(*) as cuantos
                                 FROM actividades WHERE ida= $request->ida");
                  $cuantos = $existe[0]->cuantos;               
            if ($cuantos== 0)
                {
                    $actividades = new actividades;
                    $actividades->ida = $request->ida;
                    $actividades->idu = $request->idu;
                    $actividades->fecha = $request->fecha;
                    $actividades->idemp = $request->idemp;
                    $actividades->save();
                }
                if ($request->idta==1)
                    {
                        $actividadesdetalles = new actividadesdetalles;
                    $actividadesdetalles->ida= $request->ida;
                    $actividadesdetalles->idtar = $request->idt;
                    $actividadesdetalles->horas = $request->horas;
                    $actividadesdetalles->total = $request->total;
                    $actividadesdetalles->detalle = $request->detalle;
                    $actividadesdetalles->partes = "NA";
                    $actividadesdetalles->save();
                    }
                 if ($request->idta==2)
                    {
                        $actividadesdetalles = new actividadesdetalles;
                    $actividadesdetalles->ida= $request->ida;
                    $actividadesdetalles->idtar = $request->idt;
                    $actividadesdetalles->horas = $request->horas;
                    $actividadesdetalles->total = $request->total;
                    $actividadesdetalles->detalle = $request->detalle;
                    $actividadesdetalles->partes = $request->partes;
                    $actividadesdetalles->save();
                    }
                

            $acticarrito = DB::select("SELECT ti.nombre AS tipo,t.nombre AS acti,ad.horas,ad.total,ad.detalle,ad.partes
FROM actividadesdetalles AS ad
INNER JOIN tarea AS t ON t.idta = ad.idtar
INNER JOIN tipos AS ti ON ti.idt = t.idt
WHERE ad.ida= $request->ida");    

            return view('actividades.carrito')->with('acticarrito',$acticarrito);
    }

    public function altaactividad()
    {
        $idu = Session()->get('idu');
        $nombregerente = Session()->get('nombre');
        $actividades = DB::select("SELECT ida FROM actividades ORDER BY ida DESC LIMIT 1");
        $cuantos = count($actividades);
        if($cuantos==0){
            $sigue=1;
        }else{
            $sigue = $actividades[0]->ida +1;
        }
        $empleados = DB::select("SELECT idemp,CONCAT(nombre,'',apellido)AS nombre
                                  FROM empleados WHERE activo='Si';");

        return view('actividades.alta')->with('sigue',$sigue)->with ('idu',$idu)
        ->with('nombregerente',$nombregerente)->with('empleados',$empleados);
    }
    public function datosemp(request $request)
    {
        $empleado = DB::select("SELECT idemp,CONCAT(nombre,'',apellido)AS nombre,edad,correo,rfc,foto
                                  FROM empleados WHERE idemp= $request->idemp");
        return view('actividades.infoemp')->with('empleado',$empleado[0]);
    }
    public function datosacti(request $request)
    {
          $tarea = DB::select("SELECT idta,nombre
                                        FROM tarea
                                        WHERE idt = $request->idta");
                return view('actividades.cargatarea')->with('tarea',$tarea);
    }
    public function infotar(request $request)
    {
        $tarea = DB::select("SELECT idt,nombre,horas,costohora
                                FROM tarea WHERE idta = $request->idta");
            return view('actividades.infotarea')->with('tarea',$tarea[0]);
    }
    public function datoscorrectivo(request $request)
    {
        if ($request->idta==2)
        {
        return view('actividades.infocorrectivo');
        } 
    }
}
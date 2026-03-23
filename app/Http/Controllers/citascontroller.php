<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clientes;
use App\Models\fecha_hora_cita;
use App\Models\citas;
use App\Models\detalles;

class citascontroller extends Controller
{
    // Carga el formulario con todos los catálogos
    public function altacita()
    {
        $ultimocliente = \DB::select("SELECT idc FROM clientes ORDER BY idc DESC LIMIT 1");
        $sigue = count($ultimocliente) == 0 ? 1 : $ultimocliente[0]->idc + 1;

        $ultimacita = \DB::select("SELECT idac FROM citas ORDER BY idac DESC LIMIT 1");
        $sigueCita  = count($ultimacita) == 0 ? 1 : $ultimacita[0]->idac + 1;

        $ultimafh = \DB::select("SELECT idfhc FROM fecha_hora_cita ORDER BY idfhc DESC LIMIT 1");
        $sigueFhc = count($ultimafh) == 0 ? 1 : $ultimafh[0]->idfhc + 1;

        $tiposcliente = \DB::select("SELECT idtc, tipo_cliente FROM tipo_cliente ORDER BY idtc ASC");
        $largosh      = \DB::select("SELECT idlch, largo FROM largo_cabello_h ORDER BY idlch ASC");
        $largosm      = \DB::select("SELECT idlcm, largo FROM largo_cabello_m ORDER BY idlcm ASC");
        $cortesh      = \DB::select("SELECT idtch, corte FROM tipo_corte_h ORDER BY idtch ASC");
        $cortesm      = \DB::select("SELECT idtcm, corte FROM tipo_corte_m ORDER BY idtcm ASC");
        $flequillos   = \DB::select("SELECT idf, flequillo FROM flequillo ORDER BY idf ASC");
        $servicios    = \DB::select("SELECT ids, servicio FROM servicios_add ORDER BY ids ASC");
        $estilos      = \DB::select("SELECT idec, estilo FROM estilo_cabello ORDER BY idec ASC");

        return view('citas.alta')
            ->with('sigue',        $sigue)
            ->with('sigueCita',    $sigueCita)
            ->with('sigueFhc',     $sigueFhc)
            ->with('tiposcliente', $tiposcliente)
            ->with('largosh',      $largosh)
            ->with('largosm',      $largosm)
            ->with('cortesh',      $cortesh)
            ->with('cortesm',      $cortesm)
            ->with('flequillos',   $flequillos)
            ->with('servicios',    $servicios)
            ->with('estilos',      $estilos);
    }

    // Guarda los datos y devuelve la vista parcial del carrito vía AJAX
    public function cargacarrito(Request $request)
    {
        $idac  = (int) $request->idac;
        $idc   = (int) $request->idc;
        $idfhc = (int) $request->idfhc;

        $existeCliente = \DB::select("SELECT COUNT(*) as cuantos FROM clientes WHERE idc = ?", [$idc]);
        if ($existeCliente[0]->cuantos == 0) {
            $cliente           = new clientes;
            $cliente->idc      = $idc;
            $cliente->nombre   = $request->nombre;
            $cliente->ap       = $request->ap;
            $cliente->telefono = $request->telefono;
            $cliente->save();
        }

        $existeFhc = \DB::select("SELECT COUNT(*) as cuantos FROM fecha_hora_cita WHERE idfhc = ?", [$idfhc]);
        if ($existeFhc[0]->cuantos == 0) {
            $fhc        = new fecha_hora_cita;
            $fhc->idfhc = $idfhc;
            $fhc->fecha = $request->fecha;
            $fhc->hora  = $request->hora;
            $fhc->save();
        }

        $existeCita = \DB::select("SELECT COUNT(*) as cuantos FROM citas WHERE idac = ?", [$idac]);
        if ($existeCita[0]->cuantos == 0) {
            $cita        = new citas;
            $cita->idac  = $idac;
            $cita->idc   = $idc;
            $cita->idfhc = $idfhc;
            $cita->save();
        }

        $det       = new detalles;
        $det->idac = $idac;
        $det->idtc = $request->idtc;
        $det->idec = $request->idec;

        if ($request->idtc == 1) {
            $det->idlch = $request->idlch ?: null;
            $det->idtch = $request->idtch ?: null;
            $det->idlcm = null;
            $det->idtcm = null;
            $det->idf   = null;
        } else {
            $det->idlcm = $request->idlcm ?: null;
            $det->idtcm = $request->idtcm ?: null;
            $det->idf   = $request->idf   ?: null;
            $det->idlch = null;
            $det->idtch = null;
        }

        $det->ids = $request->ids ?: null;
        $det->save();

        $carrito = \DB::select("
            SELECT
                tc.tipo_cliente                  AS genero,
                COALESCE(lh.largo,  lm.largo)    AS largo,
                COALESCE(ch.corte,  cm.corte)    AS corte,
                COALESCE(f.flequillo, 'N/A')     AS flequillo,
                ec.estilo,
                COALESCE(sa.servicio, 'Ninguno') AS servicio
            FROM detalles AS d
            INNER JOIN tipo_cliente    AS tc ON tc.idtc  = d.idtc
            INNER JOIN estilo_cabello  AS ec ON ec.idec  = d.idec
            LEFT  JOIN largo_cabello_h AS lh ON lh.idlch = d.idlch
            LEFT  JOIN largo_cabello_m AS lm ON lm.idlcm = d.idlcm
            LEFT  JOIN tipo_corte_h    AS ch ON ch.idtch = d.idtch
            LEFT  JOIN tipo_corte_m    AS cm ON cm.idtcm = d.idtcm
            LEFT  JOIN flequillo       AS f  ON f.idf    = d.idf
            LEFT  JOIN servicios_add   AS sa ON sa.ids   = d.ids
            WHERE d.idac = ?
            ORDER BY d.idd ASC
        ", [$idac]);

        return view('citas.carrito')->with('carrito', $carrito);
    }

    // Reporte general con conteo de servicios por cliente
    public function reporte()
    {
        $reporte = \DB::select("
            SELECT
                cl.idc,
                cl.nombre,
                cl.ap,
                cl.telefono,
                fhc.fecha,
                fhc.hora,
                tc.tipo_cliente AS genero,
                COUNT(d.idd)    AS num_servicios
            FROM citas AS c
            INNER JOIN clientes        AS cl  ON cl.idc    = c.idc
            INNER JOIN fecha_hora_cita AS fhc ON fhc.idfhc = c.idfhc
            INNER JOIN detalles        AS d   ON d.idac    = c.idac
            INNER JOIN tipo_cliente    AS tc  ON tc.idtc   = d.idtc
            GROUP BY cl.idc, cl.nombre, cl.ap, cl.telefono,
                     fhc.fecha, fhc.hora, tc.tipo_cliente
            ORDER BY c.idac DESC
        ");

        return view('citas.reporte')->with('reporte', $reporte);
    }
}
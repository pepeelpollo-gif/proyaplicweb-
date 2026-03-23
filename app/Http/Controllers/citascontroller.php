<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clientes;
use App\Models\fecha_hora_cita;
use App\Models\citas;
use App\Models\detalles;

class citascontroller extends Controller
{
    // ── Muestra el formulario de alta ────────────────────────────
    public function altacita()
    {
        // Siguiente IDC disponible
        $ultimocliente = \DB::select("SELECT idc FROM clientes ORDER BY idc DESC LIMIT 1");
        $sigue = count($ultimocliente) == 0 ? 1 : $ultimocliente[0]->idc + 1;

        // Catálogos para los selects
        $tiposcliente  = \DB::select("SELECT idtc, tipo_cliente FROM tipo_cliente ORDER BY idtc ASC");
        $largosh       = \DB::select("SELECT idlch, largo FROM largo_cabello_h ORDER BY idlch ASC");
        $largosm       = \DB::select("SELECT idlcm, largo FROM largo_cabello_m ORDER BY idlcm ASC");
        $cortesh       = \DB::select("SELECT idtch, corte FROM tipo_corte_h ORDER BY idtch ASC");
        $cortesm       = \DB::select("SELECT idtcm, corte FROM tipo_corte_m ORDER BY idtcm ASC");
        $flequillos    = \DB::select("SELECT idf, flequillo FROM flequillo ORDER BY idf ASC");
        $servicios     = \DB::select("SELECT ids, servicio FROM servicios_add ORDER BY ids ASC");
        $estilos       = \DB::select("SELECT idec, estilo FROM estilo_cabello ORDER BY idec ASC");

        return view('citas.alta')
            ->with('sigue',       $sigue)
            ->with('tiposcliente', $tiposcliente)
            ->with('largosh',      $largosh)
            ->with('largosm',      $largosm)
            ->with('cortesh',      $cortesh)
            ->with('cortesm',      $cortesm)
            ->with('flequillos',   $flequillos)
            ->with('servicios',    $servicios)
            ->with('estilos',      $estilos);
    }

    // ── Carga el carrito vía AJAX (igual que cargacarrito en actividades) ──
    public function cargacarrito(Request $request)
    {
        // 1. Verificar si el cliente ya existe, si no, insertarlo
        $existeCliente = \DB::select("SELECT COUNT(*) as cuantos FROM clientes WHERE idc = $request->idc");
        if ($existeCliente[0]->cuantos == 0) {
            $cliente           = new clientes;
            $cliente->idc      = $request->idc;
            $cliente->nombre   = $request->nombre;
            $cliente->ap       = $request->ap;
            $cliente->telefono = $request->telefono;
            $cliente->save();
        }

        // 2. Verificar si la fecha/hora ya existe, si no, insertarla
        $existeFhc = \DB::select("SELECT COUNT(*) as cuantos FROM fecha_hora_cita WHERE idfhc = $request->idfhc");
        if ($existeFhc[0]->cuantos == 0) {
            $fhc        = new fecha_hora_cita;
            $fhc->idfhc = $request->idfhc;
            $fhc->fecha = $request->fecha;
            $fhc->hora  = $request->hora;
            $fhc->save();
        }

        // 3. Verificar si la cita (cabecera) ya existe, si no, insertarla
        $existeCita = \DB::select("SELECT COUNT(*) as cuantos FROM citas WHERE idac = $request->idac");
        if ($existeCita[0]->cuantos == 0) {
            $cita        = new citas;
            $cita->idac  = $request->idac;
            $cita->idc   = $request->idc;
            $cita->idfhc = $request->idfhc;
            $cita->save();
        }

        // 4. Insertar el detalle del servicio
        $det        = new detalles;
        $det->idac  = $request->idac;
        $det->idtc  = $request->idtc;
        $det->idec  = $request->idec;

        // Campos condicionales por género
        if ($request->idtc == 1) {
            // Hombre
            $det->idlch = $request->idlch;
            $det->idtch = $request->idtch;
            $det->idlcm = null;
            $det->idtcm = null;
            $det->idf   = null;
        } else {
            // Mujer
            $det->idlcm = $request->idlcm;
            $det->idtcm = $request->idtcm;
            $det->idf   = ($request->idf != '' ? $request->idf : null);
            $det->idlch = null;
            $det->idtch = null;
        }

        $det->ids = ($request->ids != '' ? $request->ids : null);
        $det->save();

        // 5. Traer el carrito actualizado para refrescar la vista parcial
        $carrito = \DB::select("
            SELECT
                d.idd,
                tc.tipo_cliente AS genero,
                COALESCE(lh.largo, lm.largo) AS largo,
                COALESCE(ch.corte, cm.corte) AS corte,
                COALESCE(f.flequillo, 'N/A') AS flequillo,
                ec.estilo,
                COALESCE(sa.servicio, '—') AS servicio
            FROM detalles AS d
            INNER JOIN tipo_cliente   AS tc ON tc.idtc  = d.idtc
            INNER JOIN estilo_cabello AS ec ON ec.idec  = d.idec
            LEFT  JOIN largo_cabello_h AS lh ON lh.idlch = d.idlch
            LEFT  JOIN largo_cabello_m AS lm ON lm.idlcm = d.idlcm
            LEFT  JOIN tipo_corte_h   AS ch ON ch.idtch = d.idtch
            LEFT  JOIN tipo_corte_m   AS cm ON cm.idtcm = d.idtcm
            LEFT  JOIN flequillo      AS f  ON f.idf    = d.idf
            LEFT  JOIN servicios_add  AS sa ON sa.ids   = d.ids
            WHERE d.idac = $request->idac
            ORDER BY d.idd ASC
        ");

        return view('citas.carrito')->with('carrito', $carrito);
    }

    // ── Reporte general ──────────────────────────────────────────
    public function reporte()
    {
        $reporte = \DB::select("
            SELECT
                c.idac,
                cl.idc,
                cl.nombre,
                cl.ap,
                cl.telefono,
                fhc.fecha,
                fhc.hora,
                tc.tipo_cliente AS genero,
                COUNT(d.idd) AS num_servicios
            FROM citas AS c
            INNER JOIN clientes        AS cl  ON cl.idc   = c.idc
            INNER JOIN fecha_hora_cita AS fhc ON fhc.idfhc = c.idfhc
            INNER JOIN detalles        AS d   ON d.idac   = c.idac
            INNER JOIN tipo_cliente    AS tc  ON tc.idtc  = d.idtc
            GROUP BY c.idac, cl.idc, cl.nombre, cl.ap, cl.telefono,
                     fhc.fecha, fhc.hora, tc.tipo_cliente
            ORDER BY c.idac DESC
        ");

        return view('citas.reporte')->with('reporte', $reporte);
    }
}

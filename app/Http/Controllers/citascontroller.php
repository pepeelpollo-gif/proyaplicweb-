<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clientes;
use App\Models\citas;
use App\Models\detalles;

class citascontroller extends Controller
{
    public function altacita()
    {
        $ultimocliente = \DB::select("SELECT idc FROM clientes ORDER BY idc DESC LIMIT 1");
        $sigue = count($ultimocliente) == 0 ? 1 : $ultimocliente[0]->idc + 1;

        // Catálogos
        $tiposcliente = \DB::select("SELECT idtc, tipo_cliente FROM tipo_cliente ORDER BY idtc ASC");

        // Largos filtrados por género (idtc=1 hombre, idtc=2 mujer)
        $largosh = \DB::select("SELECT idlcm, largo FROM largo_cabello_m WHERE idtc = 1 ORDER BY idlcm ASC");
        $largosm = \DB::select("SELECT idlcm, largo FROM largo_cabello_m WHERE idtc = 2 ORDER BY idlcm ASC");

        // Cortes filtrados por género
        $cortesh = \DB::select("SELECT idtch, corte FROM tipo_corte_h WHERE idtc = 1 ORDER BY idtch ASC");
        $cortesm = \DB::select("SELECT idtch, corte FROM tipo_corte_h WHERE idtc = 2 ORDER BY idtch ASC");

        $flequillos = \DB::select("SELECT idf, flequillo FROM flequillo ORDER BY idf ASC");
        $servicios  = \DB::select("SELECT ids, servicio FROM servicios_add ORDER BY ids ASC");
        $estilos    = \DB::select("SELECT idec, estilo FROM estilo_cabello ORDER BY idec ASC");

        // Clientes registrados para el select
        $clientes = \DB::select("SELECT idc, nombre, ap, telefono FROM clientes ORDER BY nombre ASC");

        return view('citas.alta')
            ->with('sigue',        $sigue)
            ->with('tiposcliente', $tiposcliente)
            ->with('largosh',      $largosh)
            ->with('largosm',      $largosm)
            ->with('cortesh',      $cortesh)
            ->with('cortesm',      $cortesm)
            ->with('flequillos',   $flequillos)
            ->with('servicios',    $servicios)
            ->with('estilos',      $estilos)
            ->with('clientes',     $clientes);
    }

    public function cargacarrito(Request $request)
    {
        $idc = (int) $request->idc;

        // 1. Guardar cliente si no existe
        $existeCliente = \DB::select("SELECT COUNT(*) as cuantos FROM clientes WHERE idc = ?", [$idc]);
        if ($existeCliente[0]->cuantos == 0) {
            $cliente           = new clientes;
            $cliente->idc      = $idc;
            $cliente->nombre   = $request->nombre;
            $cliente->ap       = $request->ap;
            $cliente->telefono = $request->telefono;
            $cliente->save();
        }

        // 2. Buscar si ya existe una cita para este cliente
        $existeCita = \DB::select("SELECT idac FROM citas WHERE idc = ? LIMIT 1", [$idc]);

        if (count($existeCita) == 0) {
            // Insertar cita con fecha, hora e idtc directamente
            \DB::insert("INSERT INTO citas (idc, fecha, hora, idtc) VALUES (?, ?, ?, ?)", [
                $idc,
                $request->fecha,
                $request->hora,
                $request->idtc
            ]);
            $idac = \DB::getPdo()->lastInsertId();
        } else {
            $idac = $existeCita[0]->idac;
        }

        // 3. Insertar detalle
        $det       = new detalles;
        $det->idac = $idac;
        $det->idtc = $request->idtc;
        $det->idec = $request->idec;

        if ($request->idtc == 1) {
            // Hombre: usa idlcm (tabla unificada) e idtch
            $det->idlcm = $request->idlch ?: null;
            $det->idtch = $request->idtch ?: null;
            $det->idf   = null;
        } else {
            // Mujer: usa idlcm e idtch (mismas columnas, distinto género)
            $det->idlcm = $request->idlcm ?: null;
            $det->idtch = $request->idtcm ?: null;
            $det->idf   = $request->idf   ?: null;
        }

        $det->ids = $request->ids ?: null;
        $det->save();

        // 4. Traer carrito actualizado
        $carrito = \DB::select("
            SELECT
                d.idd,
                d.idac,
                tc.tipo_cliente                  AS genero,
                COALESCE(lc.largo, 'N/A')        AS largo,
                COALESCE(co.corte, 'N/A')        AS corte,
                COALESCE(f.flequillo, 'N/A')     AS flequillo,
                ec.estilo,
                COALESCE(sa.servicio, 'Ninguno') AS servicio
            FROM detalles AS d
            INNER JOIN tipo_cliente    AS tc ON tc.idtc  = d.idtc
            INNER JOIN estilo_cabello  AS ec ON ec.idec  = d.idec
            LEFT  JOIN largo_cabello_m AS lc ON lc.idlcm = d.idlcm
            LEFT  JOIN tipo_corte_h    AS co ON co.idtch = d.idtch
            LEFT  JOIN flequillo       AS f  ON f.idf    = d.idf
            LEFT  JOIN servicios_add   AS sa ON sa.ids   = d.ids
            WHERE d.idac = ?
            ORDER BY d.idd ASC
        ", [$idac]);

        return view('citas.carrito')->with('carrito', $carrito);
    }

    public function eliminadetalle(Request $request)
    {
        $idd  = (int) $request->idd;
        $idac = (int) $request->idac;

        \DB::delete("DELETE FROM detalles WHERE idd = ?", [$idd]);

        $carrito = \DB::select("
            SELECT
                d.idd,
                d.idac,
                tc.tipo_cliente                  AS genero,
                COALESCE(lc.largo, 'N/A')        AS largo,
                COALESCE(co.corte, 'N/A')        AS corte,
                COALESCE(f.flequillo, 'N/A')     AS flequillo,
                ec.estilo,
                COALESCE(sa.servicio, 'Ninguno') AS servicio
            FROM detalles AS d
            INNER JOIN tipo_cliente    AS tc ON tc.idtc  = d.idtc
            INNER JOIN estilo_cabello  AS ec ON ec.idec  = d.idec
            LEFT  JOIN largo_cabello_m AS lc ON lc.idlcm = d.idlcm
            LEFT  JOIN tipo_corte_h    AS co ON co.idtch = d.idtch
            LEFT  JOIN flequillo       AS f  ON f.idf    = d.idf
            LEFT  JOIN servicios_add   AS sa ON sa.ids   = d.ids
            WHERE d.idac = ?
            ORDER BY d.idd ASC
        ", [$idac]);

        return view('citas.carrito')->with('carrito', $carrito);
    }

    public function reporte()
    {
        $reporte = \DB::select("
            SELECT
                cl.idc,
                cl.nombre,
                cl.ap,
                cl.telefono,
                c.fecha,
                c.hora,
                tc.tipo_cliente AS genero,
                COUNT(d.idd)    AS num_servicios
            FROM citas AS c
            INNER JOIN clientes     AS cl ON cl.idc   = c.idc
            INNER JOIN tipo_cliente AS tc ON tc.idtc  = c.idtc
            INNER JOIN detalles     AS d  ON d.idac   = c.idac
            GROUP BY cl.idc, cl.nombre, cl.ap, cl.telefono,
                     c.fecha, c.hora, tc.tipo_cliente
            ORDER BY c.idac ASC
        ");

        return view('citas.reporte')->with('reporte', $reporte);
    }
}
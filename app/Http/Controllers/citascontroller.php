<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clientes;
use App\Models\fecha_hora_cita;
use App\Models\citas;
use App\Models\detalles;

class citascontroller extends Controller
{
    public function altacita()
    {
        $ultimocliente = \DB::select("SELECT idc FROM clientes ORDER BY idc DESC LIMIT 1");
        $sigue = count($ultimocliente) == 0 ? 1 : $ultimocliente[0]->idc + 1;

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
            ->with('tiposcliente', $tiposcliente)
            ->with('largosh',      $largosh)
            ->with('largosm',      $largosm)
            ->with('cortesh',      $cortesh)
            ->with('cortesm',      $cortesm)
            ->with('flequillos',   $flequillos)
            ->with('servicios',    $servicios)
            ->with('estilos',      $estilos);
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

        // 2. Guardar fecha/hora — dejar que AUTO_INCREMENT genere el ID
        //    Solo insertar una vez por sesion de cita (verificamos por idc en citas)
        $existeCita = \DB::select("SELECT idac, idfhc FROM citas WHERE idc = ? LIMIT 1", [$idc]);

        if (count($existeCita) == 0) {
            // Insertar fecha_hora_cita y obtener el ID generado
            \DB::insert("INSERT INTO fecha_hora_cita (fecha, hora) VALUES (?, ?)", [
                $request->fecha,
                $request->hora
            ]);
            $idfhc = \DB::getPdo()->lastInsertId();

            // Insertar cita cabecera
            \DB::insert("INSERT INTO citas (idc, idfhc) VALUES (?, ?)", [$idc, $idfhc]);
            $idac = \DB::getPdo()->lastInsertId();
        } else {
            $idac  = $existeCita[0]->idac;
            $idfhc = $existeCita[0]->idfhc;
        }

        // 3. Insertar detalle
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

        // 4. Traer carrito actualizado
        $carrito = \DB::select("
    SELECT
        d.idd,
        d.idac,
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
            ORDER BY c.idac ASC
        ");

        return view('citas.reporte')->with('reporte', $reporte);
    }

    public function eliminadetalle(Request $request)
{
    $idd  = (int) $request->idd;
    $idac = (int) $request->idac;

    \DB::delete("DELETE FROM detalles WHERE idd = ?", [$idd]);

    $carrito = \DB::select("
        SELECT
            d.idd,
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
}
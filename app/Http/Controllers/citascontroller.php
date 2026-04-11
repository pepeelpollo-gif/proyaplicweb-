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

        $tiposcliente = \DB::select("SELECT idtc, tipo_cliente FROM tipo_cliente ORDER BY idtc ASC");
        $largosh      = \DB::select("SELECT idlcm, largo FROM largo_cabello_m WHERE idtc = 1 ORDER BY idlcm ASC");
        $largosm      = \DB::select("SELECT idlcm, largo FROM largo_cabello_m WHERE idtc = 2 ORDER BY idlcm ASC");
        $cortesh      = \DB::select("SELECT idtch, corte FROM tipo_corte_h WHERE idtc = 1 ORDER BY idtch ASC");
        $cortesm      = \DB::select("SELECT idtch, corte FROM tipo_corte_h WHERE idtc = 2 ORDER BY idtch ASC");
        $flequillos   = \DB::select("SELECT idf, flequillo FROM flequillo ORDER BY idf ASC");
        $servicios    = \DB::select("SELECT ids, servicio FROM servicios_add ORDER BY ids ASC");
        $estilos      = \DB::select("SELECT idec, estilo FROM estilo_cabello ORDER BY idec ASC");
        $clientes     = \DB::select("SELECT idc, nombre, ap, telefono FROM clientes ORDER BY nombre ASC");

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

    /* ═══════════════════════════════════════════════════════════
       cargacarrito — Agrega un servicio al carrito (AJAX)
       ✦ Validación de seguridad backend añadida
       ═══════════════════════════════════════════════════════════ */
    public function cargacarrito(Request $request)
    {
        // ── VALIDACIÓN BACKEND ───────────────────────────────────
        $errores = $this->validarDatosCliente($request);

        if (!empty($errores)) {
            // Devuelve JSON de error; el frontend puede manejarlo
            // (en este flujo jQuery/AJAX el carrito simplemente no se carga)
            return response()->json(['ok' => false, 'errores' => $errores], 422);
        }
        // ────────────────────────────────────────────────────────

        $idc = (int) $request->idc;

        $existeCliente = \DB::select("SELECT COUNT(*) as cuantos FROM clientes WHERE idc = ?", [$idc]);
        if ($existeCliente[0]->cuantos == 0) {
            $cliente           = new clientes;
            $cliente->idc      = $idc;
            $cliente->nombre   = trim($request->nombre);
            $cliente->ap       = trim($request->ap);
            $cliente->telefono = trim($request->telefono);
            $cliente->save();
        }

        $existeCita = \DB::select("SELECT idac FROM citas WHERE idc = ? LIMIT 1", [$idc]);

        if (count($existeCita) == 0) {
            \DB::insert("INSERT INTO citas (idc, fecha, hora, idtc) VALUES (?, ?, ?, ?)", [
                $idc, $request->fecha, $request->hora, $request->idtc
            ]);
            $idac = \DB::getPdo()->lastInsertId();
        } else {
            $idac = $existeCita[0]->idac;
        }

        $det       = new detalles;
        $det->idac = $idac;
        $det->idtc = $request->idtc;
        $det->idec = $request->idec;

        if ($request->idtc == 1) {
            $det->idlcm = $request->idlch ?: null;
            $det->idtch = $request->idtch ?: null;
            $det->idf   = null;
        } else {
            $det->idlcm = $request->idlcm ?: null;
            $det->idtch = $request->idtcm ?: null;
            $det->idf   = $request->idf   ?: null;
        }

        $det->ids = $request->ids ?: null;
        $det->save();

        return view('citas.carrito')->with('carrito', $this->getCarrito($idac));
    }

    /* ═══════════════════════════════════════════════════════════
       eliminadetalle — Elimina un detalle por idd (AJAX)
       ═══════════════════════════════════════════════════════════ */
    public function eliminadetalle(Request $request)
    {
        $idd  = (int) $request->idd;
        $idac = (int) $request->idac;

        \DB::delete("DELETE FROM detalles WHERE idd = ?", [$idd]);

        return view('citas.carrito')->with('carrito', $this->getCarrito($idac));
    }

    /* ═══════════════════════════════════════════════════════════
       reporte — Lista general de citas
       ═══════════════════════════════════════════════════════════ */
    public function reporte()
    {
        $reporte = \DB::select("
            SELECT
                c.idac,
                cl.idc,
                cl.nombre,
                cl.ap,
                cl.telefono,
                c.fecha,
                c.hora,
                tc.tipo_cliente AS genero,
                COUNT(d.idd)    AS num_servicios
            FROM citas AS c
            INNER JOIN clientes     AS cl ON cl.idc  = c.idc
            INNER JOIN tipo_cliente AS tc ON tc.idtc = c.idtc
            INNER JOIN detalles     AS d  ON d.idac  = c.idac
            GROUP BY c.idac, cl.idc, cl.nombre, cl.ap, cl.telefono,
                     c.fecha, c.hora, tc.tipo_cliente
            ORDER BY c.idac ASC
        ");

        return view('citas.reporte')->with('reporte', $reporte);
    }

    /* ═══════════════════════════════════════════════════════════
       modificacita — Formulario de modificación
       ═══════════════════════════════════════════════════════════ */
    public function modificacita(Request $request)
    {
        $idac = (int) $request->idac;

        $cita = \DB::select("
            SELECT c.idac, c.idc, c.fecha, c.hora, c.idtc,
                   cl.nombre, cl.ap, cl.telefono
            FROM citas AS c
            INNER JOIN clientes AS cl ON cl.idc = c.idc
            WHERE c.idac = ?
            LIMIT 1
        ", [$idac]);

        if (count($cita) == 0) {
            return redirect()->route('reportecitas');
        }

        $cita    = $cita[0];

        $detalle = \DB::select("SELECT * FROM detalles WHERE idac = ? LIMIT 1", [$idac]);
        $detalle = count($detalle) > 0 ? $detalle[0] : null;

        $todosDetalles = $this->getCarrito($idac);

        $tiposcliente = \DB::select("SELECT idtc, tipo_cliente FROM tipo_cliente ORDER BY idtc ASC");
        $largosh      = \DB::select("SELECT idlcm, largo FROM largo_cabello_m WHERE idtc = 1 ORDER BY idlcm ASC");
        $largosm      = \DB::select("SELECT idlcm, largo FROM largo_cabello_m WHERE idtc = 2 ORDER BY idlcm ASC");
        $cortesh      = \DB::select("SELECT idtch, corte FROM tipo_corte_h WHERE idtc = 1 ORDER BY idtch ASC");
        $cortesm      = \DB::select("SELECT idtch, corte FROM tipo_corte_h WHERE idtc = 2 ORDER BY idtch ASC");
        $flequillos   = \DB::select("SELECT idf, flequillo FROM flequillo ORDER BY idf ASC");
        $servicios    = \DB::select("SELECT ids, servicio FROM servicios_add ORDER BY ids ASC");
        $estilos      = \DB::select("SELECT idec, estilo FROM estilo_cabello ORDER BY idec ASC");

        return view('citas.modificar')
            ->with('cita',          $cita)
            ->with('detalle',       $detalle)
            ->with('todosDetalles', $todosDetalles)
            ->with('tiposcliente',  $tiposcliente)
            ->with('largosh',       $largosh)
            ->with('largosm',       $largosm)
            ->with('cortesh',       $cortesh)
            ->with('cortesm',       $cortesm)
            ->with('flequillos',    $flequillos)
            ->with('servicios',     $servicios)
            ->with('estilos',       $estilos);
    }

    /* ═══════════════════════════════════════════════════════════
       guardamodifica — Guarda los cambios de una cita
       ✦ Validación de seguridad backend añadida
       ═══════════════════════════════════════════════════════════ */
    public function guardamodifica(Request $request)
    {
        $idac = (int) $request->idac;

        // ── VALIDACIÓN BACKEND (segunda línea de defensa) ────────
        // El teléfono en la vista de modificar es readonly, pero
        // validamos igualmente para proteger contra peticiones directas.
        $telefono = trim($request->telefono ?? '');
        if ($telefono !== '' && !ctype_digit($telefono)) {
            return back()->withErrors([
                'telefono' => 'El teléfono solo puede contener dígitos numéricos.'
            ])->withInput();
        }
        // ────────────────────────────────────────────────────────

        \DB::update("UPDATE citas SET fecha = ?, hora = ?, idtc = ? WHERE idac = ?", [
            $request->fecha, $request->hora, $request->idtc, $idac
        ]);

        $detalle = \DB::select("SELECT idd FROM detalles WHERE idac = ? LIMIT 1", [$idac]);

        if (count($detalle) > 0) {
            $idd = $detalle[0]->idd;

            if ($request->idtc == 1) {
                \DB::update("UPDATE detalles SET idtc=?, idlcm=?, idtch=?, idf=NULL, idec=?, ids=? WHERE idd=?", [
                    $request->idtc,
                    $request->idlch ?: null,
                    $request->idtch ?: null,
                    $request->idec  ?: null,
                    $request->ids   ?: null,
                    $idd
                ]);
            } else {
                \DB::update("UPDATE detalles SET idtc=?, idlcm=?, idtch=?, idf=?, idec=?, ids=? WHERE idd=?", [
                    $request->idtc,
                    $request->idlcm ?: null,
                    $request->idtcm ?: null,
                    $request->idf   ?: null,
                    $request->idec  ?: null,
                    $request->ids   ?: null,
                    $idd
                ]);
            }
        }

        return redirect()->route('reportecitas');
    }

    /* ═══════════════════════════════════════════════════════════
       MÉTODOS PRIVADOS
       ═══════════════════════════════════════════════════════════ */

    /**
     * getCarrito — Obtiene todos los detalles de una cita con JOINs
     */
    private function getCarrito($idac)
    {
        return \DB::select("
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
    }

    /**
     * validarDatosCliente — Validación de seguridad en backend
     *
     * Reglas:
     *   - nombre / ap : solo letras, acentos y espacios
     *   - telefono    : exactamente 10 dígitos numéricos (ctype_digit + strlen)
     *
     * Devuelve array vacío si todo es válido, o array con mensajes de error.
     */
    private function validarDatosCliente(Request $request): array
    {
        $errores  = [];
        $nombre   = trim($request->nombre ?? '');
        $ap       = trim($request->ap     ?? '');
        $telefono = trim($request->telefono ?? '');

        // Validar Nombre
        if ($nombre === '') {
            $errores['nombre'] = 'El nombre es obligatorio.';
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
            $errores['nombre'] = 'El nombre solo puede contener letras y espacios.';
        }

        // Validar Apellido
        if ($ap === '') {
            $errores['ap'] = 'El apellido es obligatorio.';
        } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $ap)) {
            $errores['ap'] = 'El apellido solo puede contener letras y espacios.';
        }

        // Validar Teléfono — ctype_digit es la verificación más estricta en PHP:
        // rechaza espacios, guiones, paréntesis y cualquier no-dígito
        if ($telefono === '') {
            $errores['telefono'] = 'El teléfono es obligatorio.';
        } elseif (!ctype_digit($telefono) || strlen($telefono) !== 10) {
            $errores['telefono'] = 'El teléfono debe contener exactamente 10 dígitos numéricos.';
        }

        return $errores;
    }
}

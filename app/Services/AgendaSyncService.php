<?php

namespace App\Services;

use App\AgendaCI;
use App\Paciente;
use App\Profesional;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AgendaSyncService
{
    /**
     * Sincroniza citas desde fac_m_citas en un rango de fechas
     *
     * @param int $diasAtras Días hacia atrás desde hoy
     * @param int $diasAdelante Días hacia adelante desde hoy
     * @return array Estadísticas de la sincronización
     */
    public function sincronizarRango($diasAtras = 2, $diasAdelante = 3)
    {
        $fechaInicio = Carbon::today()->subDays($diasAtras)->format('Y-m-d') . ' 00:00:00';
        $fechaFin    = Carbon::today()->addDays($diasAdelante)->format('Y-m-d') . ' 23:59:59';

        // CRÍTICO: Usar whereRaw con CONVERT para evitar problemas con SQL Server
        $citas = DB::connection('sqlsrv1')
            ->table('fac_m_citas')
            ->whereRaw("FECHA >= CONVERT(datetime, '$fechaInicio', 120)")
            ->whereRaw("FECHA <= CONVERT(datetime, '$fechaFin', 120)")
            ->whereNotNull('CODIGO_USUARIO')
            ->orderBy('FECHA')
            ->get();

        $creados = 0;
        $actualizados = 0;

        foreach ($citas as $cita) {
            $idRegistro    = trim($cita->ID_REGISTRO);
            $codigoUsuario = trim($cita->CODIGO_USUARIO);

            // Mapear tipo de documento
            $tipoDoc = $this->mapTipoDoc((int)$cita->TIPDOCUM);
            $cedula  = trim($cita->NUMDOCUM);

            // Construir nombre completo del paciente
            $nombrePaciente = trim(
                trim($cita->NOMBRE1) . ' ' .
                trim($cita->NOMBRE2) . ' ' .
                trim($cita->APELLIDO1) . ' ' .
                trim($cita->APELLIDO2)
            );

            // Buscar o crear paciente
            $paciente = Paciente::firstOrCreate(
                [
                    'tipo_documento'   => $tipoDoc,
                    'numero_documento' => $cedula
                ],
                [
                    'nombres'          => trim(trim($cita->NOMBRE1) . ' ' . trim($cita->NOMBRE2)),
                    'apellidos'        => trim(trim($cita->APELLIDO1) . ' ' . trim($cita->APELLIDO2)),
                    'telefono'         => trim($cita->TELEFONO ?? ''),
                    'historia_clinica' => trim($cita->HISTORIA ?? ''),
                ]
            );

            // Buscar profesional por codigo_usuario
            $profesional = Profesional::where('codigo_usuario', $codigoUsuario)->first();

            // Preparar datos de la agenda
            $datos = [
                'fecha'              => Carbon::parse($cita->FECHA)->format('Y-m-d H:i:s'),
                'codigo_consultorio' => trim($cita->CODIGO ?? ''),
                'historia'           => trim($cita->HISTORIA ?? ''),
                'paciente_id'        => $paciente->id,
                'paciente_nombre'    => $nombrePaciente,
                'paciente_cedula'    => $cedula,
                'paciente_tipo_doc'  => $tipoDoc,
                'paciente_telefono'  => trim($cita->TELEFONO ?? ''),
                'profesional_id'     => $profesional ? $profesional->id : null,
                'codigo_usuario'     => $codigoUsuario,
                'cups_codigo'        => trim($cita->CODIGO_CUPS ?? ''),
                'contrato'           => trim($cita->CONTRATO ?? ''),
                'empresafac'         => trim($cita->EMPRESAFAC ?? ''),
                'llegada_confirmada' => !empty(trim($cita->NUMERO_FACTURA ?? '')),
                'numero_factura'     => !empty(trim($cita->NUMERO_FACTURA ?? '')) ? trim($cita->NUMERO_FACTURA) : null,
                'atencion_factura'   => !empty($cita->ATENCION_FACTURA) ? Carbon::parse($cita->ATENCION_FACTURA)->format('Y-m-d H:i:s') : null,
                'sincronizado_at'    => now(),
            ];

            // Verificar si ya existe
            $existe = AgendaCI::where('id_registro', $idRegistro)->first();

            if ($existe) {
                $existe->update($datos);
                $actualizados++;
            } else {
                AgendaCI::create(array_merge(['id_registro' => $idRegistro], $datos));
                $creados++;
            }
        }

        return [
            'creados'      => $creados,
            'actualizados' => $actualizados,
            'total'        => count($citas)
        ];
    }

    /**
     * Mapea el tipo de documento desde el código numérico de SQL Server
     *
     * @param int $tipo
     * @return string
     */
    private function mapTipoDoc($tipo)
    {
        return match($tipo) {
            1 => 'CC',  // Cédula de Ciudadanía
            2 => 'TI',  // Tarjeta de Identidad
            3 => 'CE',  // Cédula de Extranjería
            4 => 'RC',  // Registro Civil
            5 => 'PA',  // Pasaporte
            default => 'CC'
        };
    }
}

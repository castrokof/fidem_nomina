<?php
// app/Services/AgendaSyncService.php

namespace App\Services;

use App\AgendaCI;
use App\Paciente;
use App\Profesional;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AgendaSyncService
{
    protected $apiService;

    public function __construct(CitasApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function sincronizarRango($diasAtras = 2, $diasAdelante = 3)
    {
        $fechaInicio = Carbon::today()->subDays($diasAtras)->format('Y-m-d');
        $fechaFin    = Carbon::today()->addDays($diasAdelante)->format('Y-m-d');

        Log::info("Iniciando sincronización: {$fechaInicio} a {$fechaFin}");

        $citas = $this->apiService->getCitasPorRango($fechaInicio, $fechaFin);

        $creados = 0;
        $actualizados = 0;
        $errores = 0;

        foreach ($citas as $cita) {
            try {
                $this->procesarCita($cita, $creados, $actualizados);
            } catch (\Exception $e) {
                $errores++;
                $idRegistro = isset($cita['ID_REGISTRO']) ? $cita['ID_REGISTRO'] : 'N/A';
                Log::error("Error procesando cita ID_REGISTRO={$idRegistro}: " . $e->getMessage());
                continue;
            }
        }

        Log::info("Sincronización completada: {$creados} creados, {$actualizados} actualizados, {$errores} errores");

        return [
            'creados'      => $creados,
            'actualizados' => $actualizados,
            'total'        => count($citas)
        ];
    }

    protected function procesarCita($cita, &$creados, &$actualizados)
{
    $idRegistro    = trim($this->getValue($cita, 'ID_REGISTRO', ''));
    $codigoUsuario = trim($this->getValue($cita, 'CODIGO_USUARIO', ''));

    if (empty($idRegistro) || empty($codigoUsuario)) {
        Log::warning("Cita sin ID_REGISTRO o CODIGO_USUARIO, omitiendo");
        return;
    }

    $tipoDoc = $this->mapTipoDoc((int) $this->getValue($cita, 'TIPDOCUM', 1));
    $cedula  = trim($this->getValue($cita, 'NUMDOCUM', ''));

    $nombrePaciente = trim(
        trim($this->getValue($cita, 'NOMBRE1', '')) . ' ' .
        trim($this->getValue($cita, 'NOMBRE2', '')) . ' ' .
        trim($this->getValue($cita, 'APELLIDO1', '')) . ' ' .
        trim($this->getValue($cita, 'APELLIDO2', ''))
    );

    $paciente = Paciente::firstOrCreate(
        [
            'tipo_documento'   => $tipoDoc,
            'numero_documento' => $cedula
        ],
        [
            'nombres'          => trim(trim($this->getValue($cita, 'NOMBRE1', '')) . ' ' . trim($this->getValue($cita, 'NOMBRE2', ''))),
            'apellidos'        => trim(trim($this->getValue($cita, 'APELLIDO1', '')) . ' ' . trim($this->getValue($cita, 'APELLIDO2', ''))),
            'telefono'         => trim($this->getValue($cita, 'TELEFONO', '')),
            'historia_clinica' => trim($this->getValue($cita, 'HISTORIA', '')),
        ]
    );

    $profesional = Profesional::where('codigo_usuario', $codigoUsuario)->first();

    $numeroFactura = trim($this->getValue($cita, 'NUMERO_FACTURA', ''));
    $atencionFactura = $this->getValue($cita, 'ATENCION_FACTURA', null);

    // ✅ Datos completos de la agenda (todos los campos de la API)
    $datos = [
        // Campos originales
        'fecha'                 => $this->parseFecha($this->getValue($cita, 'FECHA', null)),
        'codigo_consultorio'    => trim($this->getValue($cita, 'CODIGO', '')),
        'historia'              => trim($this->getValue($cita, 'HISTORIA', '')),
        'paciente_id'           => $paciente->id,
        'paciente_nombre'       => $nombrePaciente,
        'paciente_cedula'       => $cedula,
        'paciente_tipo_doc'     => $tipoDoc,
        'paciente_telefono'     => trim($this->getValue($cita, 'TELEFONO', '')),
        'profesional_id'        => $profesional ? $profesional->id : null,
        'codigo_usuario'        => $codigoUsuario,
        'cups_codigo'           => trim($this->getValue($cita, 'CODIGO_CUPS', '')),
        'contrato'              => trim($this->getValue($cita, 'CONTRATO', '')),
        'empresafac'            => trim($this->getValue($cita, 'EMPRESAFAC', '')),
        'llegada_confirmada'    => !empty($numeroFactura),
        'numero_factura'        => !empty($numeroFactura) ? $numeroFactura : null,
        'atencion_factura'      => $this->parseFecha($atencionFactura),
        
        // ✅ NUEVOS: Campos adicionales de la API
        'orden'                 => trim($this->getValue($cita, 'ORDEN', '')),
        'fecha_solicitud'       => $this->parseFecha($this->getValue($cita, 'FECHA_SOLICITUD', null)),
        'fecha_solicitada'      => $this->parseFecha($this->getValue($cita, 'FECHA_SOLICITADA', null)),
        'tipo_solicitud'        => trim($this->getValue($cita, 'TIPO_SOLICITUD', '')),
        'ips'                   => trim($this->getValue($cita, 'IPS', '')),
        'centroprod'            => trim($this->getValue($cita, 'CENTROPROD', '')),
        'tipdocum'              => trim($this->getValue($cita, 'TIPDOCUM', '')),
        'numdocum'              => trim($this->getValue($cita, 'NUMDOCUM', '')),
        'nombre1'               => trim($this->getValue($cita, 'NOMBRE1', '')),
        'nombre2'               => trim($this->getValue($cita, 'NOMBRE2', '')),
        'apellido1'             => trim($this->getValue($cita, 'APELLIDO1', '')),
        'apellido2'             => trim($this->getValue($cita, 'APELLIDO2', '')),
        'nuevo'                 => trim($this->getValue($cita, 'NUEVO', '0')),
        'estado'                => trim($this->getValue($cita, 'ESTADO', '')),
        'atendido'              => trim($this->getValue($cita, 'ATENDIDO', '')),
        'observaciones'         => trim($this->getValue($cita, 'OBSERVACIONES', '')),
        'usuario_externo'       => $this->getValue($cita, 'USUARIO_EXTERNO', null),
        'ips_factura'           => trim($this->getValue($cita, 'IPS_FACTURA', '')),
        'documento_factura'     => trim($this->getValue($cita, 'DOCUMENTO_FACTURA', '')),
        'px_factura'            => trim($this->getValue($cita, 'PX_FACTURA', '')),
        'cupo_web'              => trim($this->getValue($cita, 'CUPO_WEB', '0')),
        'cups_descripcion'      => trim($this->getValue($cita, 'CODIGO_CUPS', '')), // Puedes ajustar si hay campo separado
        'ips_internacion'       => $this->getValue($cita, 'IPS_INTERNACION', null),
        'documento_internacion' => $this->getValue($cita, 'DOCUMENTO_INTERNACION', null),
        'orden_internacion'     => $this->getValue($cita, 'ORDEN_INTERNACION', null),
        'atencion_internacion'  => $this->parseFecha($this->getValue($cita, 'ATENCION_INTERNACION', null)),
        'px_internacion'        => trim($this->getValue($cita, 'PX_INTERNACION', '')),
        'embarazo'              => trim($this->getValue($cita, 'EMBARAZO', '0')),
        'regimenfac'            => trim($this->getValue($cita, 'REGIMENFAC', '')),
        'nivelfac'              => trim($this->getValue($cita, 'NIVELFAC', '')),
        'tipoafilfac'           => trim($this->getValue($cita, 'TIPOAFILFAC', '')),
        
        'sincronizado_at'       => now(),
    ];

    $existe = AgendaCI::where('id_registro', $idRegistro)->first();

    if ($existe) {
        $existe->update($datos);
        $actualizados++;
    } else {
        AgendaCI::create(array_merge(['id_registro' => $idRegistro], $datos));
        $creados++;
    }
}

    protected function getValue($array, $key, $default = null)
    {
        if (!is_array($array)) {
            return $default;
        }
        if (isset($array[$key]) && $array[$key] !== '') {
            return $array[$key];
        }
        return $default;
    }

    protected function parseFecha($fecha)
    {
        if (empty($fecha)) {
            return null;
        }
        try {
            // Las fechas vienen como "2026-03-28 07:30:00.000"
            return Carbon::parse($fecha)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            Log::warning("Fecha inválida: " . print_r($fecha, true));
            return null;
        }
    }

    private function mapTipoDoc($tipo)
    {
        $mapa = [
            1 => 'CC',
            2 => 'TI',
            3 => 'CE',
            4 => 'RC',
            5 => 'PA',
        ];
        $tipoInt = (int) $tipo;
        if (isset($mapa[$tipoInt])) {
            return $mapa[$tipoInt];
        }
        return 'CC';
    }
}
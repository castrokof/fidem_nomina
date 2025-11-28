<?php

namespace App\Services;

use App\Models\Nomina\EmpleadosNovedades;
use App\Models\Nomina\NominaNovedadesAplicadas;
use Carbon\Carbon;

class NovedadesNominaService
{
    /**
     * Calcular novedades para un empleado en un período específico
     *
     * @param int $empleadoId ID del empleado
     * @param string $fechaInicio Fecha inicio del período de nómina
     * @param string $fechaFin Fecha fin del período de nómina
     * @param int $nominaliquidId ID del registro de nómina (opcional, para registrar aplicación)
     * @return array Array con cálculos de novedades
     */
    public function calcularNovedades($empleadoId, $fechaInicio, $fechaFin, $nominaliquidId = null)
    {
        $fechaInicio = Carbon::parse($fechaInicio);
        $fechaFin = Carbon::parse($fechaFin);

        // Buscar novedades activas del empleado que se cruzan con el período
        $novedades = EmpleadosNovedades::where('empleado_id', $empleadoId)
            ->where('estado', 'activo')
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                $query->where(function($q) use ($fechaInicio, $fechaFin) {
                    // Novedad comienza antes y termina dentro del período
                    $q->where('fecha_inicio', '<=', $fechaInicio)
                      ->where('fecha_fin', '>=', $fechaInicio)
                      ->where('fecha_fin', '<=', $fechaFin);
                })
                ->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                    // Novedad comienza y termina dentro del período
                    $q->where('fecha_inicio', '>=', $fechaInicio)
                      ->where('fecha_fin', '<=', $fechaFin);
                })
                ->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                    // Novedad comienza dentro y termina después del período
                    $q->where('fecha_inicio', '>=', $fechaInicio)
                      ->where('fecha_inicio', '<=', $fechaFin)
                      ->where('fecha_fin', '>=', $fechaFin);
                })
                ->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                    // Novedad abarca todo el período
                    $q->where('fecha_inicio', '<=', $fechaInicio)
                      ->where('fecha_fin', '>=', $fechaFin);
                })
                ->orWhere(function($q) use ($fechaInicio) {
                    // Novedades sin fecha fin (abiertas)
                    $q->where('fecha_inicio', '<=', $fechaInicio)
                      ->whereNull('fecha_fin');
                });
            })
            ->get();

        $resultado = [
            'descuento_incapacidad' => 0,
            'descuento_suspension' => 0,
            'pago_vacaciones' => 0,
            'otros_descuentos' => 0,
            'otros_bonos' => 0,
            'dias_afectados' => 0,
            'novedades_detalle' => []
        ];

        foreach ($novedades as $novedad) {
            // Calcular días que se cruzan con el período de nómina
            $inicioNovedad = Carbon::parse($novedad->fecha_inicio);
            $finNovedad = $novedad->fecha_fin ? Carbon::parse($novedad->fecha_fin) : $fechaFin;

            // Ajustar fechas al período de nómina
            $inicioCalculo = $inicioNovedad->greaterThan($fechaInicio) ? $inicioNovedad : $fechaInicio;
            $finCalculo = $finNovedad->lessThan($fechaFin) ? $finNovedad : $fechaFin;

            $diasAplicados = $inicioCalculo->diffInDays($finCalculo) + 1;
            $valorAplicado = 0;
            $tipoAfectacion = 'neutro';

            // Calcular impacto según tipo de novedad
            switch ($novedad->tipo_novedad) {
                case 'incapacidad':
                    // Incapacidad: descuento proporcional
                    $valorAplicado = $novedad->valor ?? 0;
                    $resultado['descuento_incapacidad'] += $valorAplicado;
                    $tipoAfectacion = 'descuento';
                    break;

                case 'suspension':
                    // Suspensión: descuento por días no trabajados
                    $valorAplicado = $novedad->valor ?? 0;
                    $resultado['descuento_suspension'] += $valorAplicado;
                    $tipoAfectacion = 'descuento';
                    break;

                case 'vacaciones':
                    // Vacaciones: se paga normalmente
                    $valorAplicado = $novedad->valor ?? 0;
                    $resultado['pago_vacaciones'] += $valorAplicado;
                    $tipoAfectacion = 'bono';
                    break;

                case 'licencia':
                    // Licencia: puede ser remunerada o no
                    if ($novedad->valor > 0) {
                        $resultado['otros_bonos'] += $novedad->valor;
                        $valorAplicado = $novedad->valor;
                        $tipoAfectacion = 'bono';
                    } else {
                        $resultado['otros_descuentos'] += abs($novedad->valor);
                        $valorAplicado = abs($novedad->valor);
                        $tipoAfectacion = 'descuento';
                    }
                    break;

                case 'permiso':
                    // Permiso: generalmente no afecta salario
                    $tipoAfectacion = 'neutro';
                    break;

                default:
                    // Otros: según el valor
                    if ($novedad->valor > 0) {
                        $resultado['otros_bonos'] += $novedad->valor;
                        $valorAplicado = $novedad->valor;
                        $tipoAfectacion = 'bono';
                    } else if ($novedad->valor < 0) {
                        $resultado['otros_descuentos'] += abs($novedad->valor);
                        $valorAplicado = abs($novedad->valor);
                        $tipoAfectacion = 'descuento';
                    }
                    break;
            }

            $resultado['dias_afectados'] += $diasAplicados;

            $resultado['novedades_detalle'][] = [
                'novedad_id' => $novedad->id,
                'tipo' => $novedad->tipo_novedad,
                'fecha_inicio' => $inicioCalculo->format('Y-m-d'),
                'fecha_fin' => $finCalculo->format('Y-m-d'),
                'dias_aplicados' => $diasAplicados,
                'valor_aplicado' => $valorAplicado,
                'tipo_afectacion' => $tipoAfectacion,
                'observacion' => $novedad->observacion
            ];

            // Si se proporcionó el ID de nómina, registrar la aplicación
            if ($nominaliquidId) {
                NominaNovedadesAplicadas::create([
                    'nominaliquid_id' => $nominaliquidId,
                    'empleado_novedad_id' => $novedad->id,
                    'tipo_novedad' => $novedad->tipo_novedad,
                    'fecha_inicio' => $inicioCalculo->format('Y-m-d'),
                    'fecha_fin' => $finCalculo->format('Y-m-d'),
                    'dias_aplicados' => $diasAplicados,
                    'valor_aplicado' => $valorAplicado,
                    'tipo_afectacion' => $tipoAfectacion,
                    'observacion' => $novedad->observacion
                ]);
            }
        }

        return $resultado;
    }

    /**
     * Calcular días trabajados efectivos considerando novedades
     *
     * @param int $diasPeriodo Total de días del período
     * @param int $diasAfectados Días afectados por novedades
     * @param array $novedadesDetalle Detalle de novedades para validar tipos
     * @return int Días efectivamente trabajados
     */
    public function calcularDiasTrabajados($diasPeriodo, $diasAfectados, $novedadesDetalle)
    {
        $diasDescuento = 0;

        foreach ($novedadesDetalle as $novedad) {
            // Solo descontar días para suspensiones e incapacidades
            if (in_array($novedad['tipo'], ['suspension', 'incapacidad'])) {
                $diasDescuento += $novedad['dias_aplicados'];
            }
        }

        return $diasPeriodo - $diasDescuento;
    }
}

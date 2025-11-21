<?php
namespace App\Imports;

use App\Models\FidemContigo\Fidemcontigo;
use App\Models\FidemContigo\Evolucion;
use App\Models\FidemContigo\Ingresoegreso;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class ImportEva implements ToCollection
{
    public $numRows = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Saltar encabezados o filas vacías
            if ($row[0] === "tipdocum" || $row[0] === null) continue;

            $idEvolucion = $row[3];
            $fechaEvolucion = Carbon::parse($row[5]); // FECHAHORA_EVOLUCION

            // 1. Buscar o crear paciente
            $fidem = Fidemcontigo::firstOrNew(['numdocum' => $row[1]]);

            // Completar datos del paciente si es nuevo
            $fidem->tipdocum = $row[0];
            $fidem->numhistoria = $row[2];
            $fidem->apellido1 = $row[9];
            $fidem->apellido2 = $row[10];
            $fidem->nombre1 = $row[11];
            $fidem->nombre2 = $row[12];
            $fidem->entidad_salud = $row[13];
            $fidem->telefono = $row[14];
            $fidem->telefono_avi = $row[15];
            $fidem->telefono_residencia = $row[16];
            $fidem->telefono_movil = $row[17];
            $fidem->save(); 

            $nuevaEva = $row[7]; // RESPUESTA
            $nuevoTipo = $row[20]; // RESPUESTA

          // 2. Verificar si ya existe la evolución
                $existeEvolucion = Evolucion::where('id_evolucion', $idEvolucion)->exists();

                // Obtener última evolución antes de crear
                $ultimaEvolucionAntes = Evolucion::where('fidemcontigos_id', $fidem->id)
                    ->orderByDesc('fechahora_evolucion')
                    ->first();

                // Si no existe, la creamos
                if (!$existeEvolucion) {
                    Evolucion::create([
                        'fidemcontigos_id' => $fidem->id,
                        'id_evolucion' => $idEvolucion,
                        'fechahora_apertura' => $row[4],
                        'fechahora_evolucion' => $row[5],
                        'cuestionario' => $row[6],
                        'respuesta' => $nuevaEva,
                        'codigo_profesional' => $row[8],
                        'dx_principal' => $row[18],
                        'dx_secondary' => $row[19],
                        'tipo_evolucion' => $row[20],
                    ]);
                }

                // 3. Actualizar Fidemcontigo si esta evolución es más reciente
                if (is_null($ultimaEvolucionAntes) || $fechaEvolucion->gt($ultimaEvolucionAntes->fechahora_evolucion)) {
                    $fidem->eva = $nuevaEva;
                    $fidem->tipo_evolucion = $nuevoTipo;
                    $fidem->fecha_ultima_evolucion = $fechaEvolucion;
                    $fidem->id_evolucion = $idEvolucion;
                    $fidem->estado = ($nuevaEva > 5) ? 'Activo' : 'Inactivo';
                    $fidem->save();

                    // Crear Ingreso si EVA > 5
                    if ($nuevaEva > 5) {
                        $yaExisteIngreso = Ingresoegreso::where('fidemcontigos_id', $fidem->id)
                            ->where('tipo', 'Ingreso')
                            ->whereDate('fecha', $fechaEvolucion->toDateString())
                            ->exists();

                        if (!$yaExisteIngreso) {
                            Ingresoegreso::create([
                                'fidemcontigos_id' => $fidem->id,
                                'tipo'             => 'Ingreso',
                                'fecha'            => $fechaEvolucion,
                                'observaciones'    => 'Ingreso automático por evolución EVA > 5',
                            ]);
                        }
                    }

                    // Crear Egreso si EVA < 6
                    if ($nuevaEva < 6) {
                        $yaExisteEgreso = Ingresoegreso::where('fidemcontigos_id', $fidem->id)
                            ->where('tipo', 'Egreso')
                            ->whereDate('fecha', $fechaEvolucion->toDateString())
                            ->exists();

                        if (!$yaExisteEgreso) {
                            Ingresoegreso::create([
                                'fidemcontigos_id' => $fidem->id,
                                'tipo'             => 'Egreso',
                                'fecha'            => $fechaEvolucion,
                                'observaciones'    => 'Egreso automático por evolución EVA < 6',
                            ]);
                        }
                    }
                }

            $this->numRows++;
        }

        return $this->numRows;
    }
}

<?php
namespace App\Imports;

use App\Models\FidemContigo\Evolucion;
use App\Models\FidemContigo\OrdenMedicamentoFiltrada;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportMedicamentos implements ToCollection
{
    public $numRows = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Saltar encabezados o filas vacías
            if ($row[0] === "ID_EVOLUCION" || $row[0] === null) continue;

            $idEvolucion = $row[0]; // ID_EVOLUCION

            // Buscar la evolución relacionada
            $evolucion = Evolucion::where('id_evolucion', $idEvolucion)->first();
            if (!$evolucion) {
                // Si no existe evolución, la puedes crear o saltar el registro
                continue;
            }

            // Insertar o actualizar el medicamento filtrado
            OrdenMedicamentoFiltrada::updateOrCreate(
                [
                    'evoluciones_id' => $evolucion->id,
                    'codigo' => $row[2], // CODIGO
                ],
                [
                    'causa' => $row[1], // CAUSA
                    'presentacion' => $row[3], // PRESENTACION
                    'nombre' => $row[4], // NOMBRE
                    'cantidad' => $row[5], // CANTIDAD
                    'administracion' => $row[6], // ADMINISTRACION
                    'dosis_cant' => $row[7], // DOSIS_CANT
                    'dosis_freq' => $row[8], // DOSIS_FREQ
                    'dosis_hora' => $row[9], // DOSIS_HORA
                    'numero_dosis' => $row[10], // NUMERO_DOSIS
                    'posologia' => $row[11], // POSOLOGIA
                    'observaciones' => $row[12], // OBSERVACIONES
                    'entregado' => false, // por defecto o según lógica
                    'observacion_entrega' => null,
                ]
            );

            $this->numRows++;
        }

        return $this->numRows;
    }
}
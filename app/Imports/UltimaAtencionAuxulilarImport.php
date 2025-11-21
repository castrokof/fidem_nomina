<?php

namespace App\Imports;

use App\Models\Paliativos\ConsultaAuxiliar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class UltimaAtencionAuxulilarImport implements ToCollection
{

    public $numRows = 0;

    // use Importable;
    /**
     * @param array $row
     *
     * @return Estados|null
     */




    public function collection(Collection $rows)
    {
        $numRows1 = 0;

        foreach ($rows as $row) {

            $numRows1++;

            $existe = ConsultaAuxiliar::where([['documento', $row[1]]])->count();


            if ($existe > 0) {

                if ($row[5] == 'NULL') {
                    $FECHA1 = null;
                } else {
                    $FECHA1 = $row[5];
                }


                if ($row[6] == 'NULL') {
                    $FECHA2 = null;
                } else {
                    $FECHA2 = $row[6];
                }


                if ($row[7] == 'NULL') {
                    $FECHA3 = null;
                } else {
                    $FECHA3 = $row[7];
                }


                if ($row[8] == 'NULL') {
                    $FECHA4 = null;
                } else {
                    $FECHA4 = $row[8];
                }


                if ($row[9] == 'NULL') {
                    $FECHA5 = null;
                } else {
                    $FECHA5 = $row[9];
                }

                if ($row[10] == 'NULL') {
                    $FECHA6 = null;
                } else {
                    $FECHA6 = $row[10];
                }
                
                 if ($row[11] == 'NULL') {
                    $FECHA7 = null;
                } else {
                    $FECHA7 = $row[11];
                }
                
                 if ($row[12] == 'NULL') {
                    $FECHA8 = null;
                } else {
                    $FECHA8 = $row[12];
                }
                
                if ($row[13] == 'NULL') {
                    $FECHA9 = null;
                } else {
                    $FECHA9 = $row[13];
                }


                ConsultaAuxiliar::where([['documento', $row[1]]])->update([
                    'tipo_documento' => $row[0],
                    'documento' => $row[1],
                    'paciente' => $row[2],
                    'dx_principal' => $row[3],
                    'dx_relacionado' => $row[4],
                    'auxiliar1' => $FECHA1,
                    'auxiliar2' => $FECHA2,
                    'auxiliar3' => $FECHA3,
                    'auxiliar4' => $FECHA4,
                    'auxiliar5' => $FECHA5,
                    'auxiliar6' => $FECHA6,
                    'auxiliar7' => $FECHA7,
                    'auxiliar8' => $FECHA8,
                    'auxiliar9' => $FECHA9,
                    'auxiliar10' => null
                ]);
            } else {
                if ($row[5] == 'NULL') {
                    $FECHA1 = null;
                } else {
                    $FECHA1 = $row[5];
                }


                if ($row[6] == 'NULL') {
                    $FECHA2 = null;
                } else {
                    $FECHA2 = $row[6];
                }


                if ($row[7] == 'NULL') {
                    $FECHA3 = null;
                } else {
                    $FECHA3 = $row[7];
                }


                if ($row[8] == 'NULL') {
                    $FECHA4 = null;
                } else {
                    $FECHA4 = $row[8];
                }


                if ($row[9] == 'NULL') {
                    $FECHA5 = null;
                } else {
                    $FECHA5 = $row[9];
                }
                if ($row[10] == 'NULL') {
                    $FECHA6 = null;
                } else {
                    $FECHA6 = $row[10];
                }
                if ($row[11] == 'NULL') {
                    $FECHA7 = null;
                } else {
                    $FECHA7 = $row[11];
                }
                 if ($row[12] == 'NULL') {
                    $FECHA8 = null;
                } else {
                    $FECHA8 = $row[12];
                }
                
                if ($row[13] == 'NULL') {
                    $FECHA9 = null;
                } else {
                    $FECHA9 = $row[13];
                }
                ConsultaAuxiliar::create([
                    'tipo_documento' => $row[0],
                    'documento' => $row[1],
                    'paciente' => $row[2],
                    'dx_principal' => $row[3],
                    'dx_relacionado' => $row[4],
                    'auxiliar1' => $FECHA1,
                    'auxiliar2' => $FECHA2,
                    'auxiliar3' => $FECHA3,
                    'auxiliar4' => $FECHA4,
                    'auxiliar5' => $FECHA5,
                    'auxiliar6' => $FECHA6,
                    'auxiliar7' => $FECHA7,
                    'auxiliar8' => $FECHA8,
                    'auxiliar9' => $FECHA9,
                    'auxiliar10' => null
                ]);
            }
        }


        return $numRows1;
    }
}

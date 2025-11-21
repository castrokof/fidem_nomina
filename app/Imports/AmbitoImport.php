<?php

namespace App\Imports;

use App\Models\Paliativos\BasePaliativos;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class AmbitoImport implements ToCollection
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
        $numRows2 = 0;

        foreach ($rows as $row) {

           

            $existe = BasePaliativos::where([['document', $row[1]], ['type', $row[3]]])->count();


            if ($existe==0 || $existe=='' || $existe == null) {
                
                 $numRows1++;
                
                    $ambito = ($row[3] == 1) ? 'AMBULATORIO' : 'DOMICILIARIO';

                    // Mover la actualización dentro de la condición
                    BasePaliativos::where([['document', $row[1]]])->update([
                        'type' => $ambito
                    ]);
            
            }else{
                
                $numRows2++;
            }


        }


       return [$numRows1, $numRows2] ;
    }


    public function getRowCount(): int
    {
        return $this->numRows;
    }


}

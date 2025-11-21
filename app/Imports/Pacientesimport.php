<?php

namespace App\Imports;


use App\Models\Paliativos\BasePaliativos;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class Pacientesimport implements ToCollection
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

            $existe = BasePaliativos::where('document', $row[5])->count();


            if ($existe>0) {

                if ($row[6] == '') {
                    $fechanacimiento = null;
                }else{
                    $fechanacimiento = Carbon::parse($row[6])->format('Y-m-d');
                }

                if ($row[14] == '') {
                    $fechaingreso = null;
                }else{
                    $fechaingreso = date('Y-m-d H:i:s', strtotime($row[14]));
                }


                if ($row[16] == '') {
                    $fechafallecimiento = null;
                }else{
                    $fechafallecimiento =date('Y-m-d H:i:s', strtotime($row[16]));
                }


            BasePaliativos::where([['document',$row[5]]])->update([

            'surname'=>$row[0],
            'ssurname'=>$row[1],
            'fname'=>$row[2],
            'sname'=>$row[3],
            'type_document'=>$row[4],
            'document'=>$row[5],
            'date_birth'=>$fechanacimiento,
            'diagnosis'=>$row[7],
            'municipality'=>$row[8],
            'address'=>$row[9],
            'celular'=>$row[10],
            'phone'=>$row[11],
            'email'=>$row[12],
            'observacion'=>$row[13],
            'date_in'=>$fechaingreso,
            'dead'=>$row[15],
            'date_dead'=> $fechafallecimiento,
            'state'=>$row[17],
            'type'=>$row[18],
            'future1'=>$row[19],
            'profesional'=>$row[20],
            'sex'=>$row[21],
            'diagn'=>$row[22],
            'ips'=>$row[23],
            'estado_paci'=>$row[24],
            'user_id'=>$row[25]
                ]);
            }else{


                if ($row[6] == '') {
                    $fechanacimiento = null;
                }else{
                    $fechanacimiento = date('Y-m-d H:i:s', strtotime($row[6]));
                }

                if ($row[14] == '') {
                    $fechaingreso = null;
                }else{
                    $fechaingreso = date('Y-m-d H:i:s', strtotime($row[14]));
                }


                if ($row[16] == '') {
                    $fechafallecimiento = null;
                }else{
                    $fechafallecimiento =date('Y-m-d H:i:s', strtotime($row[16]));
                }

                BasePaliativos::create([
                    'surname'=>$row[0],
                    'ssurname'=>$row[1],
                    'fname'=>$row[2],
                    'sname'=>$row[3],
                    'type_document'=>$row[4],
                    'document'=>$row[5],
                    'date_birth'=>$fechanacimiento,
                    'diagnosis'=>$row[7],
                    'municipality'=>$row[8],
                    'address'=>$row[9],
                    'celular'=>$row[10],
                    'phone'=>$row[11],
                    'email'=>$row[12],
                    'observacion'=>$row[13],
                    'date_in'=>$fechaingreso,
                    'dead'=>$row[15],
                    'date_dead'=> $fechafallecimiento,
                    'state'=>$row[17],
                    'type'=>$row[18],
                    'future1'=>$row[19],
                    'profesional'=>$row[20],
                    'sex'=>$row[21],
                    'diagn'=>$row[22],
                    'ips'=>$row[23],
                    'estado_paci'=>$row[24],
                    'user_id'=>$row[24]
                ]);
            }




        }


       return $numRows1;
    }



}

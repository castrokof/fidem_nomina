<?php

namespace App\Http\Controllers\Nomina;
use App\Http\Controllers\Controller;
use App\Models\Nomina\Hoursxuser;
use App\Models\Seguridad\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LiquidationxuserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function informes(Request $request )
    {


        if($request->ajax()){


             //Variables donde se extrae solo la fecha

        $fechaini = new Carbon($request->fechaini);
        $fechaini = $fechaini->toDateString();

        $fechafin = new Carbon($request->fechafin);
        $fechafin = $fechafin->toDateString();
        
        
            // Obtén las variables del request
        $quincena = $request->quincena;
        $tipocontrato = $request->contrato;
    
       /* // Paso 1: Realiza la consulta básica y almacénala en una colección
        $datas = DB::table('nominaliquids')
            ->join('empleados', 'nominaliquids.empleado_id', '=', 'empleados.id')
            ->select(
                'nominaliquids.salary',
                'nominaliquids.value_salary_add',
                'nominaliquids.value_transporte',
                'nominaliquids.salary_ps',
                'nominaliquids.hours as horas',
                'nominaliquids.value_hour as valor_hora',
                'nominaliquids.type_salary',
                'nominaliquids.type_contrat',
                'nominaliquids.id',
                'empleados.id as empleado_id',
                'empleados.pnombre',
                'empleados.snombre',
                'empleados.papellido',
                'empleados.sapellido',
                'nominaliquids.quincena',
                'empleados.ips',
                'nominaliquids.value_salary_add as rodamiento',
                'nominaliquids.value_transporte as value_transporte',
                'nominaliquids.value_add_security_social as retencion',
            );
    
        // Agrega filtros condicionalmente
        if (!empty($quincena)) {
            $datas->where('nominaliquids.quincena', $quincena);
        }
    
        if (!empty($tipocontrato)) {
            $datas->where('nominaliquids.type_contrat', $tipocontrato);
        }
    
        // Obtén los datos sin cálculos complejos y agrupa por campos requeridos
        $datas = $datas->groupBy('nominaliquids.salary',
            'nominaliquids.value_salary_add',
            'nominaliquids.value_transporte',
            'nominaliquids.salary_ps',
            'nominaliquids.hours',
            'nominaliquids.value_hour',
            'nominaliquids.type_salary',
            'nominaliquids.type_contrat',
            'nominaliquids.id',
            'nominaliquids.quincena',
            'empleados.id',
            'empleados.pnombre',
            'empleados.snombre',
            'empleados.papellido',
            'empleados.sapellido',
            'nominaliquids.value_add_security_social',
            'empleados.ips')->get();
    
 
    
    
      // Paso 2: Realiza los cálculos en cada registro
        $datas->transform(function ($record) {
            
            // Calcula `total_pagar` basado en el tipo de salario y contrato
            if ($record->type_salary === 'FIJO-QUINCENAL-MENSUAL' && $record->type_contrat === 'CT') {
                
                $record->total_pagar = $record->salary + $record->value_salary_add + $record->value_transporte - ($record->salary * 0.08 + $record->value_add_security_social);
            
                
            } elseif ($record->type_salary === 'FIJO-MENSUAL') {
                
                $record->total_pagar = $record->salary + $record->value_salary_add - ($record->salary * 0.08 + $record->value_add_security_social);
            
                
            } elseif ($record->type_salary === 'FIJO-QUINCENAL-MENSUAL' && $record->type_contrat === 'PS') {
            
                $record->total_pagar = $record->salary_ps;
            
            } else {
               
                $record->total_pagar = 0;
            
                
            }
    
            // Calcula otros campos como `parafiscales` y `total_hours_value`
            $record->parafiscales = in_array($record->type_salary, ['FIJO-QUINCENAL-MENSUAL', 'FIJO-MENSUAL']) ? $record->salary * 0.08 : 0;
            $record->total = $record->value_hour * $record->hours;
    
            // Devuelve el registro con los cálculos
            return $record;
        });*/

           //$usuario = $request->usuario; no se define en el front
            $quincena = $request->quincena;
            $tipocontrato = $request->contrato;


            $datas = DB::table('nominaliquids')
            ->join('empleados', 'nominaliquids.empleado_id', '=', 'empleados.id')
            ->select(
            DB::raw("CASE WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL-MENSUAL' AND nominaliquids.type_contrat = 'CT' THEN SUM(nominaliquids.salary + nominaliquids.value_salary_add + nominaliquids.value_transporte - (nominaliquids.salary * 0.08 + (nominaliquids.value_add_security_social))) WHEN
            nominaliquids.type_salary = 'FIJO-MENSUAL' THEN SUM(nominaliquids.salary + nominaliquids.value_salary_add - (nominaliquids.salary * 0.08  + (nominaliquids.value_add_security_social)) ) WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL-MENSUAL' AND nominaliquids.type_contrat = 'PS' THEN SUM(nominaliquids.salary_ps)  ELSE 0 END as total_pagar"),
            DB::raw("CASE WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL-MENSUAL' THEN nominaliquids.hours WHEN nominaliquids.type_salary = 'FIJO-MENSUAL' THEN nominaliquids.hours ELSE 0 END as horas"),
            DB::raw('(nominaliquids.value_salary_add) as rodamiento'),
            DB::raw('(nominaliquids.value_transporte) as value_transporte '),
            DB::raw('nominaliquids.value_hour * sum(nominaliquids.hours) as total'),
            DB::raw("CASE WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL-MENSUAL' AND nominaliquids.type_contrat = 'CT' THEN nominaliquids.salary WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL-MENSUAL' AND nominaliquids.type_contrat = 'PS' THEN nominaliquids.salary_ps
            WHEN nominaliquids.type_salary = 'FIJO-MENSUAL' AND nominaliquids.type_contrat = 'CT' THEN nominaliquids.salary  ELSE 0 END as salary"),
            DB::raw("CASE WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL-MENSUAL' THEN nominaliquids.salary * 0.08 WHEN nominaliquids.type_salary = 'FIJO-MENSUAL' THEN nominaliquids.salary * 0.08 ELSE 0 END as parafiscales"),
            'nominaliquids.id as id','empleados.id as idu', 'nominaliquids.type_salary as type_salary', 'empleados.pnombre as pnombre', 'nominaliquids.value_hour as valor_hora',
            'empleados.snombre as snombre', 'empleados.papellido as papellido', 'empleados.sapellido as sapellido','nominaliquids.quincena as quincena',
             'empleados.ips as ips', 'nominaliquids.type_contrat as type_contrat', 'nominaliquids.value_add_security_social as retencion');
             
             
             
            
            // Condicionalmente agrega filtros
            if (!empty($quincena)) {
                $datas->where('nominaliquids.quincena', $quincena);
            }
            
            if (!empty($tipocontrato)) {
                $datas->where('nominaliquids.type_contrat', $tipocontrato);
            }
            
            
           
           
            
            
            if ($request->session()->get('rol_id') == 1) {
                $datas->whereIn('empleados.ips', ['ATENCION FIDEM SAS','SALUD VITALIA SAS', 'SALUD MEDCOL SAS']);
            }else if ($request->session()->get('ips') == 'ATENCION FIDEM SAS' && $request->session()->get('rol_id') == 4) {
                $datas->whereIn('empleados.ips', ['ATENCION FIDEM SAS']);
            }else if ($request->session()->get('ips') == 'SALUD VITALIA SAS' || $request->session()->get('ips') == 'SALUD MEDCOL SAS' && $request->session()->get('rol_id') == 4) {
                $datas->whereIn('empleados.ips', ['SALUD VITALIA SAS', 'SALUD MEDCOL SAS']);
            }
            


            
            // Continúa con el groupBy y obtiene los resultados
            $datas = $datas->groupBy('pnombre', 'id', 'snombre', 'papellido', 'idu', 'sapellido', 'quincena', 'valor_hora', 'salary', 'type_salary', 'ips', 'parafiscales', 'rodamiento', 'horas', 'type_contrat', 'value_transporte', 'retencion')
                ->get(); 
            
            
            return  DataTables()->of($datas)
                ->addColumn('action', function($datas){
                    $button ='<button type="button" name="novedad" id="'.$datas->id.'" value="'.$datas->idu.'" class="listasDetalleNove btn btn-sm bg-success tooltipsC" title="Adicionar Novedad"  ><span class="badge bg-teal"><i class="fa fa-fw fa-plus-circle"></i>Add</span></button>';

                    return $button;

            })
            ->rawColumns(['action'])
            ->make(true);



        }


        return view('nomina.liquidacion.informes.informes-liquid');


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//Function para informes del supervisor widgets
public function informes1(Request $request )
{

   if($request->ajax()){

    $fechaini = new Carbon($request->fechaini);
    $fechaini = $fechaini->toDateString();

    $fechafin = new Carbon($request->fechafin);
    $fechafin = $fechafin->toDateString();

    $usuario = $request->usuario;
    $valor_hora_add = 0;

//Consulta de suma de total horas
        $datas = DB::table('hoursxuser')
        ->join('usuario', 'hoursxuser.user_id', '=', 'usuario.id')
        ->where([
        ['hoursxuser.user_id', $usuario],
        ['hoursxuser.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
        ['hoursxuser.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
        ['hoursxuser.supervisor', '=', null]
        ])
        ->select(DB::raw('sum(hoursxuser.hours) as horas'))
        ->get();




//Consulta de cuenta de turnos de noche
        $turn_night = DB::table('hoursxuser')
        ->join('usuario', 'hoursxuser.user_id', '=', 'usuario.id')
        ->where([
        ['hoursxuser.user_id', $usuario],
        ['hoursxuser.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
        ['hoursxuser.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
        ['hoursxuser.working_type', '=', 'Nocturno'],
        ['hoursxuser.supervisor', '=', null]
        ])
        ->select(DB::raw('count(hoursxuser.working_type) as turnos'))
        ->get();
//Consulta de cuenta de turnos de noche
        $turn_night1 = DB::table('hoursxuser')
        ->join('usuario', 'hoursxuser.user_id', '=', 'usuario.id')
        ->where([
        ['hoursxuser.user_id', $usuario],
        ['hoursxuser.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
        ['hoursxuser.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
        ['hoursxuser.working_type', '=', 'Nocturno'],
        ['hoursxuser.supervisor', '=', null]
        ])
        ->select(DB::raw('count(hoursxuser.working_type) as turnos1'))
        ->first();

//Consulta de total horas - horas base
        $horas_base = 0;
        $hours_total = DB::table('hoursxuser')
        ->join('usuario', 'hoursxuser.user_id', '=', 'usuario.id')
        ->where([
        ['hoursxuser.user_id', $usuario],
        ['hoursxuser.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
        ['hoursxuser.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
        ['hoursxuser.supervisor', '=', null]
        ])
        ->select(DB::raw('sum(hoursxuser.hours) as horas'))
        ->first();

        if($hours_total->horas <= 0 ){

        $horas_add = 0;

        }else if($hours_total->horas <= 96 && $hours_total->horas > 0){

        $horas_add = 0;
        $horas_base = $hours_total->horas;

        }else{

        $horas_add = $hours_total->horas - 96;
        $horas_base = $hours_total->horas - $horas_add;
        }


// validación para controlar el error de hora

        if($usuario != null){
        //Consulta para traer el valor de la hora del usuario
        $valor_hora = DB::table('usuario')
        ->join('position', 'usuario.cargo_id', '=', 'position.id')
        ->where('usuario.id', $usuario)
        ->select(DB::raw('position.value_hour as hora'))
        ->first();
         //Consulta para traer el valor del turno de noche
         $valor_turn_night = DB::table('usuario')
         ->join('position', 'usuario.cargo_id', '=', 'position.id')
         ->where('usuario.id', $usuario)
         ->select(DB::raw('position.value_hour_night as night'))
         ->first();

        //Consulta para traer la suma de total horas del usuario
        $payment = DB::table('hoursxuser')
        ->join('usuario', 'hoursxuser.user_id', '=', 'usuario.id')
        ->join('position', 'usuario.cargo_id', '=', 'position.id')
        ->where([
        ['hoursxuser.user_id', $usuario],
        ['hoursxuser.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
        ['hoursxuser.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
        ['hoursxuser.supervisor', '=', null]
                   ])
        ->select(DB::raw('sum(hoursxuser.hours) as sumhour'))
        ->first();

            $valor_turn_night_add = $valor_turn_night->night * $turn_night1->turnos1;

            $payment_day = ($valor_hora->hora * $payment->sumhour) + $valor_turn_night_add;
            $valor_hora_add = $valor_hora->hora;
        }else{

            $payment_day = 0;
        }




        return response()->json(['result' => $datas, 'result1' => $turn_night, 'result2' => $payment_day, 'result3' => $horas_base, 'result4' => $horas_add, 'valor_hora' => $valor_hora_add]);


      }





}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

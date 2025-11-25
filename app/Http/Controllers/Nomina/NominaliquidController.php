<?php

namespace App\Http\Controllers\Nomina;
use App\Http\Controllers\Controller;
use App\Models\nomina\Empleados;
use App\Models\Nomina\nominaliquid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NominaliquidController extends Controller
{

//Function para mostrar datos en el index de turnos
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

                $usuario = $request->usuario;
                $quincena = $request->quincena;


                $datas = DB::table('nominaliquids')
                ->join('empleados', 'nominaliquids.empleado_id', '=', 'empleados.id')

                ->select(
                DB::raw("CASE WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL' THEN SUM((nominaliquids.salary/2) + (nominaliquids.value_salary_add/2) - ((nominaliquids.salary/2) * 0.08)) WHEN
                nominaliquids.type_salary = 'FIJO-MENSUAL' THEN SUM(nominaliquids.salary + nominaliquids.value_salary_add - (nominaliquids.salary * 0.08)) ELSE 0 END as total_pagar"),
                DB::raw("CASE WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL' THEN hoursxuser.hours WHEN nominaliquids.type_salary = 'FIJO-MENSUAL' THEN hoursxuser.hours*2 ELSE 0 END as horas"),
                DB::raw('(nominaliquids.value_salary_add/2) as rodamiento'),
                DB::raw('nominaliquids.value_hour * sum(hoursxuser.hours) as total'),
                DB::raw("CASE WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL' THEN nominaliquids.salary/2 WHEN nominaliquids.type_salary = 'FIJO-MENSUAL' THEN nominaliquids.salary ELSE 0 END as salary"),
                DB::raw("CASE WHEN nominaliquids.type_salary = 'FIJO-QUINCENAL' THEN nominaliquids.salary/2 * 0.08 WHEN nominaliquids.type_salary = 'FIJO-MENSUAL' THEN nominaliquids.salary * 0.08 ELSE 0 END as parafiscales"),
                'nominaliquids.id as id','nominaliquids.user_id as idu', 'nominaliquids.type_salary as type_salary', 'empleados.pnombre as pnombre', 'nominaliquids.value_hour as valor_hora',
                'empleados.snombre as snombre', 'empleados.papellido as papellido', 'empleados.sapellido as sapellido','nominaliquids.quincena as quincena',
                 'empleados.ips as ips')
                ->where([
                ['nominaliquids.quincena', $quincena],
                ['nominaliquids.supervisor', '!=', null]])
                ->groupBy('pnombre', 'id', 'snombre', 'papellido', 'idu', 'sapellido', 'quincena', 'value_hour', 'salary', 'type_salary', 'ips', 'parafiscales', 'position.value_salary_add', 'horas')
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


// Index de Nomina Fija
    public function index_nominaf(Request $request )
    {

        $fechaAi=now()->toDateString()." 00:00:01";
        $fechaAf=now()->toDateString()." 23:59:59";

        if($request->ajax()){

            // Solo Fijos
            //$datas = Empleados::where([['ips', $request->ips],['type_salary', 'FIJO-QUINCENAL-MENSUAL']])
            //->get();
            
            // Todos
            $datas = Empleados::where([['ips', $request->ips]])
            ->get();
            
           


            return  DataTables()->of($datas)
           ->addColumn('action', function($datas){
                $button ='<input type="checkbox" name="case[]"  value="'.$datas->id.'" class="case btn btn-primary btn-sm tooltipsC" title="Selecciona Orden"/>';
                return $button;

            })
            ->rawColumns(['action'])
            ->make(true);





    }
    return view('nomina.nomina_fijos.index');
}

//Function para traer tabla de empleados a informes
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function informes2(Request $request )
    {


        if($request->ajax()){


             //Variables donde se extrae solo la fecha

        $fechaini = new Carbon($request->fechaini);
        $fechaini = $fechaini->toDateString();

        $fechafin = new Carbon($request->fechafin);
        $fechafin = $fechafin->toDateString();

            $usuario = $request->usuario;


            $datas = DB::table('nominaliquids')
            ->join('usuario', 'nominaliquids.user_id', '=', 'usuario.id')
            ->select('nominaliquids.id as id', 'usuario.pnombre as pnombre',  'usuario.snombre as snombre', 'usuario.papellido as papellido', 'usuario.sapellido as sapellido', 'nominaliquids.date_hour_initial_turn as date_hour_initial_turn', 'nominaliquids.date_hour_end_turn as date_hour_end_turn', 'nominaliquids.hours as hours',
            'nominaliquids.working_type as working_type', 'nominaliquids.quincena as quincena', 'nominaliquids.observation as observation', 'nominaliquids.created_at as created_at')
            ->where([
            ['nominaliquids.user_id', $usuario],
            ['nominaliquids.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
            ['nominaliquids.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
            ['nominaliquids.supervisor', '=', null]])

            ->orderBy('nominaliquids.id')
            ->get();

            return  DataTables()->of($datas)
                ->addColumn('action', function($datas){
                $button ='<input type="checkbox" name="case[]"  value="'.$datas->id.'" class="case btn btn-primary btn-sm tooltipsC" title="Selecciona Orden"/>';
                return $button;

            })
            ->rawColumns(['action'])
            ->make(true);



        }


        return view('nomina.liquidacion.informes');


    }


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
            $datas = DB::table('nominaliquids')
            ->join('usuario', 'nominaliquids.user_id', '=', 'usuario.id')
            ->where([
            ['nominaliquids.user_id', $usuario],
            ['nominaliquids.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
            ['nominaliquids.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
            ['nominaliquids.supervisor', '=', null]
            ])
            ->select(DB::raw('sum(nominaliquids.hours) as horas'))
            ->get();




  //Consulta de cuenta de turnos de noche
            $turn_night = DB::table('nominaliquids')
            ->join('usuario', 'nominaliquids.user_id', '=', 'usuario.id')
            ->where([
            ['nominaliquids.user_id', $usuario],
            ['nominaliquids.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
            ['nominaliquids.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
            ['nominaliquids.working_type', '=', 'Nocturno'],
            ['nominaliquids.supervisor', '=', null]
            ])
            ->select(DB::raw('count(nominaliquids.working_type) as turnos'))
            ->get();
  //Consulta de cuenta de turnos de noche
            $turn_night1 = DB::table('nominaliquids')
            ->join('usuario', 'nominaliquids.user_id', '=', 'usuario.id')
            ->where([
            ['nominaliquids.user_id', $usuario],
            ['nominaliquids.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
            ['nominaliquids.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
            ['nominaliquids.working_type', '=', 'Nocturno'],
            ['nominaliquids.supervisor', '=', null]
            ])
            ->select(DB::raw('count(nominaliquids.working_type) as turnos1'))
            ->first();

  //Consulta de total horas - horas base
            $horas_base = 0;
            $hours_total = DB::table('nominaliquids')
            ->join('usuario', 'nominaliquids.user_id', '=', 'usuario.id')
            ->where([
            ['nominaliquids.user_id', $usuario],
            ['nominaliquids.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
            ['nominaliquids.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
            ['nominaliquids.supervisor', '=', null]
            ])
            ->select(DB::raw('sum(nominaliquids.hours) as horas'))
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
            $payment = DB::table('nominaliquids')
            ->join('usuario', 'nominaliquids.user_id', '=', 'usuario.id')
            ->join('position', 'usuario.cargo_id', '=', 'position.id')
            ->where([
            ['nominaliquids.user_id', $usuario],
            ['nominaliquids.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
            ['nominaliquids.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
            ['nominaliquids.supervisor', '=', null]
                       ])
            ->select(DB::raw('sum(nominaliquids.hours) as sumhour'))
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

// Revisión de supervisor

public function supervisar(Request $request)
{

  if (request()->ajax()) {

    $ids = $request->input('id');


if($request->supervisor != null){

  foreach ($ids as $id ) {

            DB::table('nominaliquids')
            ->where([
                     ['id', '=', $id],
                    ])
            ->update([
                'supervisor' => $request->supervisor,
                'is_locked' => true  // Bloquear la nómina después de supervisar
            ]);

         }


        return response()->json(['success1' => 'ok1']);


    }else{

        return response()->json(['success1' => 'ng']);
    }

  }


}
//Guardar nomina pagos fijos
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_nominaf(Request $request)
    {
        $rules = array(
            'date_hour_initial_turn'  => 'required',
            'date_hour_end_turn'  => 'required',
            'working_type'  => 'required',
            'observation'  => 'max:100'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }


        //Validar que no pueda ingresar un misma turno con una misma fecha del mismo empleado

        //Variables donde se extrae solo la fecha

        $usuariosn = null;
        $usuarios = null;

        $datei = new Carbon($request->date_hour_initial_turn);
        $datei = $datei->toDateString();

        $datef = new Carbon($request->date_hour_end_turn);
        $datef = $datef->toDateString();
        $ids = $request->input('id');
        $user_id = $request->session()->get('usuario_id');
        $salario = 0;
        $rodamiento = 0;
        $salario_ps = 0;
        $seguridad_social = 0;
        $hours=0;
        $aux_transporte=0;
        
        foreach ($ids as $id ) {


//Validar que un usuario que ya está con quincena liquidada y el mismo tipo de contrato no le permita crear una nuevamente

        $existe = nominaliquid::where([
            ['working_type', $request->working_type],
            ['quincena', $request->quincena],
            ['empleado_id', $id]
            ])->count();
            
            //dd($existe);

        $empleados = Empleados::all()->where('ips',$request->ips)->where('id',$id);
        
         
         if($existe == 0){

             
            
           
             foreach ($empleados as $empleado ) {
                 
                  if($empleado->type_salary == 'FIJO-QUINCENAL-MENSUAL' && $empleado->type_contrat == 'CT' ){
                   $salario = ($empleado->salary)/2;+
                   $rodamiento = ($empleado->value_salary_add)/2;
                   $aux_transporte = ($empleado->value_transporte)/2;
                   $retencion = ($empleado->value_add_security_social)/2;
                   $hours = 120;
                   
               }else if($empleado->type_salary == 'FIJO-QUINCENAL-MENSUAL' && $empleado->type_contrat == 'PS' ){
                   
                   $salario_ps = ($empleado->salary_ps)/2;
                   $rodamiento = ($empleado->value_salary_add)/2;
                   $aux_transporte=0;
                   $retencion = ($empleado->value_add_security_social)/2;
                   $hours = 120;
                    
               }

            nominaliquid::create([
               'date_hour_initial_turn'  => $request->date_hour_initial_turn,
               'date_hour_end_turn'  => $request->date_hour_end_turn,
               'working_type'  => $request->working_type,
               'day_work'  => 15,
               'hours'  => $hours,
               'quincena'  => $request->quincena,
               'observation'  => $request->observation,
               'position' => $empleado->position,
               'eps' => $empleado->eps,
               'arl' => $empleado->arl,
               'afp' => $empleado->afp,
               'fc' => $empleado->fc,
               'salary' => $salario,
               'salary_ps' => $salario_ps,
               'value_hour' => $empleado->value_hour,
               'value_patient_attended' => $empleado->value_patient_attended,
               'value_add_security_social' => $retencion,
               'value_transporte' =>  $aux_transporte,
               'value_salary_add' => $rodamiento,
               'name_bank' => $empleado->name_bank,
               'account' => $empleado->account,
               'type_account' => $empleado->type_account,
               'type_contrat' => $empleado->type_contrat,
               'type_salary' => $empleado->type_salary,
               'date_in' => $empleado->date_in,
               'date_out' => $empleado->date_out,
               'date_incontrat' => $empleado->date_incontrat,
               'date_endcontrat' => $empleado->date_endcontrat,
               'empleado_id' => $id,
               'user_id'  => $user_id,
               'supervisor'  => $request->supervisor,
               'is_locked' => false  // Iniciar desbloqueada

               ]);


            $usuariosn[] = $id ;

             }
             
       }else if($datei > $datef){

            return response()->json(['errors' => ['La fecha y hora inicial debe ser menor que la fecha y hora final']]);

       }else if ($existe>0) {

            $usuarios[] = $id ;


       }



     }
        if ($usuarios == 0) {
            $usuarios = [];# code...
        }
        if ($usuariosn == 0) {
            $usuariosn = [];
        }
            return response()->json(['success' => 'ok', 'usuarios' => $usuarios, 'usuarios1' => $usuariosn]);

        }
        
        
        
        //Informe de nomina fijos para liquidación final
        
         public function informesf()
    {


        return view('nomina.nomina_fijos.informes.informesliquid');


    }

 public function informesf1(Request $request )
    {


        if($request->ajax()){


             //Variables donde se extrae solo la fecha

        $fechaini = new Carbon($request->fechaini);
        $fechaini = $fechaini->toDateString();

        $fechafin = new Carbon($request->fechafin);
        $fechafin = $fechafin->toDateString();

            $usuario = $request->usuario;


            $datas = DB::table('nominaliquids')
            ->join('usuario', 'nominaliquids.user_id', '=', 'usuario.id')
            ->select('nominaliquids.id as id', 'usuario.pnombre as pnombre',  'usuario.snombre as snombre', 'usuario.papellido as papellido', 'usuario.sapellido as sapellido', 'nominaliquids.date_hour_initial_turn as date_hour_initial_turn', 'nominaliquids.date_hour_end_turn as date_hour_end_turn', 'nominaliquids.hours as hours',
            'nominaliquids.working_type as working_type', 'nominaliquids.quincena as quincena', 'nominaliquids.observation as observation', 'nominaliquids.created_at as created_at')
            ->where([
            ['nominaliquids.user_id', $usuario],
            ['nominaliquids.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
            ['nominaliquids.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
            ['nominaliquids.supervisor', '=', null]])

            ->orderBy('nominaliquids.id')
            ->get();

            return  DataTables()->of($datas)
                ->addColumn('action', function($datas){
                $button ='<input type="checkbox" name="case[]"  value="'.$datas->id.'" class="case btn btn-primary btn-sm tooltipsC" title="Selecciona Orden"/>';
                return $button;

            })
            ->rawColumns(['action'])
            ->make(true);



        }


        


    }

        public function select(Request $request)
        {
            
            
            $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');


            array_push($array, nominaliquid::select('quincena')->where(
             'quincena', 'LIKE', '%' . $term . '%')
             ->groupby('quincena')
             ->get());

            return response()->json(['array'=>$array]);
        }else {



                array_push($array, nominaliquid::select('quincena')
                ->groupby('quincena')
                ->get());


                return response()->json(['array'=>$array]);



        }   
            
            /*if(request()->ajax())
            {
              $quincenas=nominaliquid::select('quincena')->groupby('quincena')->get();
                return response()->json($quincenas);
            }*/
        }






//Guardar turnos
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'date_hour_initial_turn'  => 'required',
            'date_hour_end_turn'  => 'required',
            'working_type'  => 'required',
            'observation'  => 'max:100'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        //Validar que no pueda ingresar una misma turno con una misma fecha del mismo empleado

        //Variables donde se extrae solo la fecha

        $datei = new Carbon($request->date_hour_initial_turn);
        $datei = $datei->toDateString();

        $datef = new Carbon($request->date_hour_end_turn);
        $datef = $datef->toDateString();



        $existe = nominaliquid::where([
            ['date_hour_initial_turn', 'LIKE', $datei.'%'],
            ['date_hour_end_turn', 'LIKE', $datef.'%'],
            ['user_id', $request->user_id],
            ['working_type', $request->working_type]
            ])->count();


      if($existe > 0){

             return response()->json(['success' => 'repeat']);

       }else if($datei > $datef){

            return response()->json(['errors' => ['La fecha y hora inicial debe ser menor que la fecha y hora final']]);

       }else{

         $hours = (strtotime($request->date_hour_end_turn) - strtotime($request->date_hour_initial_turn))/3600;

         nominaliquid::create([
            'date_hour_initial_turn'  => $request->date_hour_initial_turn,
            'date_hour_end_turn'  => $request->date_hour_end_turn,
            'working_type'  => $request->working_type,
            'quincena'  => $request->quincena,
            'observation'  => $request->observation,
            'hours' => $hours,
            'user_id'  => $request->user_id,
            'is_locked' => false  // Iniciar desbloqueada

            ]);

            return response()->json(['success' => 'ok']);

        }

    }

//Mostrar turnos
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

//Consultar turno a editar
     public function edit($id)
    {


        if(request()->ajax()){
            $data = nominaliquid::where('id', '=', $id)->first();
            return response()->json(['result'=>$data]);

        }


    }

//Update de turno
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Verificar si la nómina está bloqueada
        $nominaliquid = nominaliquid::findOrFail($id);

        if($nominaliquid->is_locked) {
            return response()->json([
                'errors' => ['Esta nómina está bloqueada y no puede ser modificada. Contacte al administrador si necesita hacer cambios.'],
                'locked' => true
            ]);
        }

        $rules = array(
            'date_hour_initial_turn'  => 'required',
            'date_hour_end_turn'  => 'required',
            'working_type'  => 'required',
            'observation'  => 'max:100'
        );

        // $attributeNames = array(
        //     "date_turn" => "Fecha Reporte",
        //     "hour_initial_turn" => "Hora Ingreso",
        //     "hour_end_turn" => "Hora Salida",
        //     "working_type" => "Jornada",
        //     "observation" => "observacion"

        //     );

        $error = Validator::make($request->all(), $rules);
      //  $error->setAttributeNames($attributeNames);

        if($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $datei = new Carbon($request->date_hour_initial_turn);
        $datei = $datei->toDateString();

        $datef = new Carbon($request->date_hour_end_turn);
        $datef = $datef->toDateString();

        if($datei > $datef){

            return response()->json(['errors' => ['La fecha y hora inicial debe ser menor que la fecha y hora final'], 'datei'=>$datei, 'datef'=>$datef]);

        }


        if(request()->ajax()){



            $hours = (strtotime($request->date_hour_end_turn) - strtotime($request->date_hour_initial_turn))/3600;





            $nominaliquid->update([
                'date_hour_initial_turn'  => $request->date_hour_initial_turn,
                'date_hour_end_turn'  => $request->date_hour_end_turn,
                'working_type'  => $request->working_type,
                'quincena'  => $request->quincena,
                'observation'  => $request->observation,
                'hours' => $hours,
                'user_id'  => $request->user_id,

                ]);

                return response()->json(['success' => 'ok1']);

            }

    }
// Funtion para eliminar turno
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(request()->ajax()){
            // Verificar si la nómina está bloqueada
            $nominaliquid = nominaliquid::findOrFail($id);

            if($nominaliquid->is_locked) {
                return response()->json([
                    'errors' => ['Esta nómina está bloqueada y no puede ser eliminada. Contacte al administrador si necesita hacer cambios.'],
                    'locked' => true
                ]);
            }

            // Si no está bloqueada, permitir la eliminación
            $nominaliquid->delete();

            return response()->json(['success' => 'ok']);
        }
    }
}

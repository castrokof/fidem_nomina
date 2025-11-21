<?php

namespace App\Http\Controllers\Nomina;

use App\Http\Controllers\Controller;
use App\Models\Nomina\Hoursxuser;
use App\Models\Seguridad\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HoursxuserController extends Controller
{

//Function para mostrar datos en el index de turnos
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {

        if($request->ajax()){

            $usuario_id = $request->session()->get('usuario_id');

            $datas = DB::table('hoursxuser')
            ->join('usuario', 'hoursxuser.user_id', '=', 'usuario.id')
            ->select('hoursxuser.id as id', 'hoursxuser.date_hour_initial_turn as date_hour_initial_turn', 'hoursxuser.date_hour_end_turn as date_hour_end_turn', 'hoursxuser.hours as hours',
            'hoursxuser.working_type as working_type', 'hoursxuser.quincena as quincena', 'hoursxuser.observation as observation', 'hoursxuser.created_at as created_at')
            ->where([['hoursxuser.user_id', $usuario_id],
                     ['hoursxuser.supervisor', "=", null]])
            ->orderBy('hoursxuser.id')
            ->get();
            return  DataTables()->of($datas)
                ->addColumn('action', function($datas){
                $button = '<button type="button" name="edit" id="'.$datas->id.'"
                class = "edit btn btn-primary btn-sm tooltipsC"  title="Editar registro" ><i class="fas fa-edit"></i> Editar</button>';

                return $button;

            })
            ->rawColumns(['action'])
            ->make(true);
                }


        return view('nomina.control_turnos.index');


    }


// Index de Nomina Fija
    public function index_nominaf1(Request $request )
    {

        $fechaAi=now()->toDateString()." 00:00:01";
        $fechaAf=now()->toDateString()." 23:59:59";

        if($request->ajax()){


            $datas = DB::table('usuario')
            ->Join('usuario_rol', 'usuario.id', '=', 'usuario_rol.usuario_id')
            ->Join('position', 'usuario.cargo_id', '=', 'position.id')
            ->Join('rol', 'usuario_rol.rol_id', '=', 'rol.id')
            ->select('usuario.id as id', 'usuario.pnombre as pnombre', 'usuario.snombre as snombre', 'usuario.papellido as papellido','usuario.sapellido as sapellido', 'rol.nombre as nombre',
            'usuario.tipo_documento as tipo_documento', 'usuario.documento as documento', 'usuario.usuario as usuario', 'position.position as cargo', 'position.salary as salario', 'usuario.celular as celular',
            'usuario.email as email', 'usuario.ips as ips', 'usuario.activo as activo', 'usuario.type_salary as type_salary', 'usuario.created_at as created_at')
            ->orderBy('usuario.id')
            ->where('usuario.ips', $request->ips)
            ->where('usuario.type_salary', 1)
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
    public function informes(Request $request )
    {


        if($request->ajax()){


             //Variables donde se extrae solo la fecha

        $fechaini = new Carbon($request->fechaini);
        $fechaini = $fechaini->toDateString();

        $fechafin = new Carbon($request->fechafin);
        $fechafin = $fechafin->toDateString();

            $usuario = $request->usuario;


            $datas = DB::table('hoursxuser')
            ->join('usuario', 'hoursxuser.user_id', '=', 'usuario.id')
            ->select('hoursxuser.id as id', 'usuario.pnombre as pnombre',  'usuario.snombre as snombre', 'usuario.papellido as papellido', 'usuario.sapellido as sapellido', 'hoursxuser.date_hour_initial_turn as date_hour_initial_turn', 'hoursxuser.date_hour_end_turn as date_hour_end_turn', 'hoursxuser.hours as hours',
            'hoursxuser.working_type as working_type', 'hoursxuser.quincena as quincena', 'hoursxuser.observation as observation', 'hoursxuser.created_at as created_at')
            ->where([
            ['hoursxuser.user_id', $usuario],
            ['hoursxuser.date_hour_initial_turn', '>=', $fechaini.' 00:00:00'],
            ['hoursxuser.date_hour_initial_turn', '<=', $fechafin.' 23:59:59'],
            ['hoursxuser.supervisor', '=', null]])

            ->orderBy('hoursxuser.id')
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

// Revisión de supervisor

public function supervisar(Request $request)
{

  if (request()->ajax()) {

    $ids = $request->input('id');


if($request->supervisor != null){

  foreach ($ids as $id ) {

            DB::table('hoursxuser')
            ->where([
                     ['id', '=', $id],
                    ])
            ->update(['supervisor' => $request->supervisor]);

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

        foreach ($ids as $id ) {



        $existe = Hoursxuser::where([
            ['working_type', $request->working_type],
            ['quincena', $request->quincena],
            ['user_id', $id]
            ])->count();


      if($existe == 0){


            Hoursxuser::create([
               'date_hour_initial_turn'  => $request->date_hour_initial_turn,
               'date_hour_end_turn'  => $request->date_hour_end_turn,
               'working_type'  => $request->working_type,
               'quincena'  => $request->quincena,
               'observation'  => $request->observation,
               'hours' => 120,
               'user_id'  => $id,
               'supervisor'  => $request->supervisor


               ]);


            $usuariosn[] = $id ;


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



        $existe = Hoursxuser::where([
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

         Hoursxuser::create([
            'date_hour_initial_turn'  => $request->date_hour_initial_turn,
            'date_hour_end_turn'  => $request->date_hour_end_turn,
            'working_type'  => $request->working_type,
            'quincena'  => $request->quincena,
            'observation'  => $request->observation,
            'hours' => $hours,
            'user_id'  => $request->user_id,

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
            $data = Hoursxuser::where('id', '=', $id)->first();
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





            Hoursxuser::findOrFail($id)
            ->update([
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
        //
    }
}

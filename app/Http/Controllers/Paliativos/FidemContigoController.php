<?php

namespace App\Http\Controllers\Paliativos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\carbon;
use App\Models\FidemContigo\Fidemcontigo;
use App\Models\FidemContigo\Evolucion;
use App\Models\FidemContigo\Seguimiento;
use App\Models\FidemContigo\OrdenMedicamentoFiltrada;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportEva;
use App\Imports\ImportMedicamentos;
// use App\Models\Paliativos\fidemcontigo\observacionesfidemcontigo;


class FidemContigoController extends Controller
{
    public function informes(Request $request)
    {

        if ($request->ajax()) {

            $fechaini = new Carbon($request->fechaini);
            $fechaini = $fechaini->toDateString();

            $fechafin = new Carbon($request->fechafin);
            $fechafin = $fechafin->toDateString();

            $usuario = $request->usuario;
            $valor_hora_add = 0;

            //Consulta de activos
            $activos = Fidemcontigo::where('estado','Activo')->count();
            
             $totalEva = Fidemcontigo::where('estado', 'Activo')
            ->select(
                 DB::raw('SUM(CASE WHEN eva = "9" THEN 1 ELSE 0 END) AS EVA9'),
                 DB::raw('SUM(CASE WHEN eva = "8" THEN 1 ELSE 0 END) AS EVA8'),
                 DB::raw('SUM(CASE WHEN eva = "7" THEN 1 ELSE 0 END) AS EVA7'),
                 DB::raw('SUM(CASE WHEN eva = "6" THEN 1 ELSE 0 END) AS EVA6'),
                    )->get();
                    
            $totales = Fidemcontigo::where('fidemcontigos.estado', 'Activo')
            ->select(
                DB::raw('COUNT(*) as total_activos'),
        
                DB::raw("SUM(CASE WHEN EXISTS (
                    SELECT 1 FROM seguimientos 
                    JOIN evoluciones ON evoluciones.id = seguimientos.evoluciones_id
                    WHERE seguimientos.fidemcontigos_id = fidemcontigos.id
                    AND seguimientos.estado_contacto = 'si'
                    AND evoluciones.id_evolucion = fidemcontigos.id_evolucion
                ) THEN 1 ELSE 0 END) as con_seguimiento"),
        
                DB::raw("SUM(CASE WHEN EXISTS (
                    SELECT 1 FROM seguimientos 
                    JOIN evoluciones ON evoluciones.id = seguimientos.evoluciones_id
                    WHERE seguimientos.fidemcontigos_id = fidemcontigos.id
                    AND seguimientos.estado_contacto = 'no'
                    AND evoluciones.id_evolucion = fidemcontigos.id_evolucion
                ) THEN 1 ELSE 0 END) as sin_contacto"),
        
                DB::raw("SUM(CASE WHEN NOT EXISTS (
                    SELECT 1 FROM seguimientos 
                    JOIN evoluciones ON evoluciones.id = seguimientos.evoluciones_id
                    WHERE seguimientos.fidemcontigos_id = fidemcontigos.id
                    AND seguimientos.estado_contacto IN ('si', 'no')
                    AND evoluciones.id_evolucion = fidemcontigos.id_evolucion
                ) THEN 1 ELSE 0 END) as sin_seguimiento")
            )
            ->first();
           

           $detalleDiagnos = DB::table('fidemcontigos')
            ->join('evoluciones', 'evoluciones.id_evolucion', '=', 'fidemcontigos.id_evolucion')
            ->where('fidemcontigos.estado', 'Activo')
            ->select('evoluciones.dx_principal', DB::raw('COUNT(*) as total'))
            ->groupBy('evoluciones.dx_principal')
            ->orderByDesc('total')
            ->limit(6)
            ->get();
            
            
             $detalleProfesional = DB::table('fidemcontigos')
            ->join('evoluciones', 'evoluciones.id_evolucion', '=', 'fidemcontigos.id_evolucion')
            ->where('fidemcontigos.estado', 'Activo')
            ->select('evoluciones.codigo_profesional', DB::raw('COUNT(*) as total'))
            ->groupBy('evoluciones.codigo_profesional')
            ->orderByDesc('total')
            ->limit(8)
            ->get();
                    


            return response()->json([
               'activos' => $activos, 'totalEva' => $totalEva, 'detallesegui' =>  $totales, 'diagnostico' => $detalleDiagnos, 'profesional' => $detalleProfesional
            ]);
        }
    }

     public function importeva(Request $request)
    {

   if($request->ajax()){


    $file5 = $request->file('file5');
    $file6 = $request->file('file6');

        if($file5 != null){

        $this->importaExcel($request);

        return response()->json(['mensaje' => 'ok']);

        }else if($file6 != null){

        $this->importaExcel2($request);

         return response()->json(['mensaje' => 'ok']);

        }else{

            return response()->json(['mensaje' => 'vacio']);//return redirect('admin/archivo')->with('mensaje', 'No seleccionaste ningun archivo');
        }

    }

   }


    public function importaExcel(Request $request)
    {

         // Guardo la colección en $file

       $file = $request->file('file5');

       $name=time().$file->getClientOriginalName();


        $destinationPath = public_path('importbd/');

        $file->move($destinationPath, $name);

        $path=$destinationPath.$name;

       // $import = new EstadosImport();

    Excel::import(new ImportEva, $path);

    }

    public function importaExcel2(Request $request)
    {

         // Guardo la colección en $file

       $file = $request->file('file6');

       $name=time().$file->getClientOriginalName();


        $destinationPath = public_path('importbd/');

        $file->move($destinationPath, $name);

        $path=$destinationPath.$name;

       // $import = new EstadosImport();

    Excel::import(new ImportMedicamentos, $path);

    }



    public function addseguimiento($id)
    {
        if (request()->ajax()) {

            $detallename = Fidemcontigo::with('evoluciones.medicamentos')->where('id', $id)->first();
            
          
            return response()->json(['add' => $detallename]);
        }
    }
    
    
    
     public function consultar_addseguimiento($id)
    {
        if (request()->ajax()) {

            $consultadetallename = Fidemcontigo::with('seguimientos.medicamentosegui', 'seguimientos.user_seguimiento')->where('id', $id)->first();
            
           
          
            return response()->json(['consultaadd' => $consultadetallename]);
        }
    }




    public function store(Request $request)
        {
            
            if($request->estado_contacto == 'no'){
                $request->validate([
                'evoluciones_id' => 'required|exists:evoluciones,id',
                'user_id' => 'required|exists:usuario,id',
                'estado_contacto' => 'required|string',
                'observacion_general' => 'nullable'
                
            ]);
                
            }else{
            
            $request->validate([
                'evoluciones_id' => 'required|exists:evoluciones,id',
                'user_id' => 'required|exists:usuario,id',
                'estado_contacto' => 'required|string',
                'todos_entregados' => 'required|string',
                'observacion_general' => 'nullable|string',
                'medicamentos' => 'array',
            ]);
            
            }
            
            try {
                
                DB::beginTransaction();
            
                $priorizado = null;
            
               if($request->evaescala>7){
            
                $priorizado = 'Priorizado';
            
                }
            
            
            $seguimiento = Seguimiento::create([
                'evoluciones_id' => $request->evoluciones_id,
                'fidemcontigos_id' => $request->fidemcontigos_id,
                'user_id' => $request->user_id,
                'estado_contacto' => $request->estado_contacto,
                'todos_entregados' => $request->todos_entregados ?? 'n/a',
                'new_eva' => $request->evaescala,
                'priorizado' => $priorizado,
                'observacion_general' => $request->observacion_general,
            ]);
        
        if ($request->has('medicamentos') && is_array($request->medicamentos)) {    
            
        foreach ($request->medicamentos as $med) {
                OrdenMedicamentoFiltrada::where('evoluciones_id', $request->evoluciones_id)
                    ->where('id', $med['id'])
                    ->update([
                        'entregado' => $med['entregado'],
                        'observacion_entrega' => $med['observacion'],
                    ]);
            }
            
        }
        
        DB::commit();
        
        
        
         return response()->json(['success' => true]);
         
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al guardar los datos.', 'error' => $e->getMessage()], 500);
        }
        
                    
          
        }


    // Método para mostrar la vista principal con datos desde SQL Server
    public function index()
    {
       
        return view('paliativos.fidemcontigo.index');
    }
    
    public function indexFidem(Request $request)
    {
        // Verifica si la solicitud es AJAX
        if ($request->ajax()) {
            // Obtiene los datos de la base de datos
            $datas = Fidemcontigo::where('fidemcontigos.estado', 'Activo')
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('evoluciones')
                            ->join('seguimientos', 'seguimientos.evoluciones_id', '=', 'evoluciones.id')
                            ->whereColumn('evoluciones.fidemcontigos_id', 'fidemcontigos.id')
                            ->whereColumn('evoluciones.id_evolucion', 'fidemcontigos.id_evolucion')
                            ->whereIn('seguimientos.estado_contacto', ['si', 'no']);
                    })->orderByDesc('fidemcontigos.eva');
                    
             if($request->fechaini != '' && $request->fechafin != '' ){
                 
                $fechaini = $request->fechaini." 00:00:01";
                $fechafin = $request->fechafin." 23:59:59";

                $datas->whereBetween('fidemcontigos.fecha_ultima_evolucion', [$fechaini, $fechafin]);
        
            }
            
            if($request->evac != ''){
        
               $datas->where('fidemcontigos.eva',$request->evac);
        
            }
            
            if($request->epsselect != '' ){
                
                                        if($request->epsselect == 'COOSALUD')
                                        
                                        $eps = ['EPS042','ESS024'];
                                
                                        else if($request->epsselect == 'COMFENALCO')
                                        
                                        $eps = ['EPS012'];
                                        
                                        else if($request->epsselect == 'SOS')
                                        
                                        $eps = ['EPS018'];
                                        
                                        else
                                        
                                        $eps = [$request->epsselect];
                                        
                
                
                 $datas->whereIn('fidemcontigos.entidad_salud',$eps);
        
            }
            
            if($request->notaevo != ''){
        
               $datas->where('fidemcontigos.tipo_evolucion',$request->notaevo);
        
            }
            
          
                    
             $datas->get();
    
            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="evolucion" id="' . $datas->id . '" class="seguimientoadd btn btn-float btn-sm btn-success tooltipsC" title="Adicionar seguimientos"  ><i class="fas fa-notes-medical "></i></button>' .
                    $button = '<button type="button" name="observacion" id="' . $datas->id . '" class="consultaseguimiento btn btn-float btn-sm btn-warning tooltipsC" title="Ver Seguimientos"  ><i class="fas fa-user-check"></i></button><br>';
    
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
           
        }
    
        // Si no es una solicitud AJAX, redirige a la vista principal
        //return view('paliativos.fidemcontigo.index');
    
    }
    
    public function indexFidem_segui(Request $request)
    {
        // Verifica si la solicitud es AJAX
        if ($request->ajax()) {
            // Obtiene los datos de la base de datos
           
            
                     
           $datas = Fidemcontigo::select('fidemcontigos.*')
                    ->where('fidemcontigos.estado', 'Activo')
                    ->join('evoluciones', 'evoluciones.fidemcontigos_id', '=', 'fidemcontigos.id')
                    ->join('seguimientos', 'seguimientos.evoluciones_id', '=', 'evoluciones.id')
                    ->whereColumn('evoluciones.id_evolucion', 'fidemcontigos.id_evolucion')
                    ->where('seguimientos.estado_contacto', 'si')
                    ->where('seguimientos.created_at', function ($query) {
                        $query->selectRaw('MAX(s2.created_at)')
                            ->from('seguimientos as s2')
                            ->whereColumn('s2.evoluciones_id', 'seguimientos.evoluciones_id');
                    })
                    ->where('seguimientos.priorizado', null)
                    ->get();
              
    
    
            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="evolucion" id="' . $datas->id . '" class="seguimientoadd btn btn-float btn-sm btn-success tooltipsC" title="Adicionar seguimientos"  ><i class="fas fa-notes-medical "></i></button>' .
                    $button = '<button type="button" name="observacion" id="' . $datas->id . '" class="consultaseguimiento btn btn-float btn-sm btn-warning tooltipsC" title="Ver Seguimientos"  ><i class="fas fa-user-check"></i></button><br>';
    
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
           
        }
    
        // Si no es una solicitud AJAX, redirige a la vista principal
        //return view('paliativos.fidemcontigo.index');
    
    }

    public function indexFidem_sincon(Request $request)
    {
        // Verifica si la solicitud es AJAX
        if ($request->ajax()) {
            // Obtiene los datos de la base de datos
            $datas = Fidemcontigo::select('fidemcontigos.*')
                    ->where('fidemcontigos.estado', 'Activo')
                    ->join('evoluciones', 'evoluciones.fidemcontigos_id', '=', 'fidemcontigos.id')
                    ->join('seguimientos', 'seguimientos.evoluciones_id', '=', 'evoluciones.id')
                    ->whereColumn('evoluciones.id_evolucion', 'fidemcontigos.id_evolucion')
                    ->where('seguimientos.estado_contacto', 'no')
                    ->where('seguimientos.created_at', function ($query) {
                        $query->selectRaw('MAX(s2.created_at)')
                            ->from('seguimientos as s2')
                            ->whereColumn('s2.evoluciones_id', 'seguimientos.evoluciones_id');
                    })
                    ->get();
    
    
            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="evolucion" id="' . $datas->id . '" class="seguimientoadd btn btn-float btn-sm btn-success tooltipsC" title="Adicionar seguimientos"  ><i class="fas fa-notes-medical "></i></button>' .
                    $button = '<button type="button" name="observacion" id="' . $datas->id . '" class="consultaseguimiento btn btn-float btn-sm btn-warning tooltipsC" title="Ver Seguimientos"  ><i class="fas fa-user-check"></i></button><br>';
    
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
           
        }
    
        // Si no es una solicitud AJAX, redirige a la vista principal
        //return view('paliativos.fidemcontigo.index');
    
    }
    
      public function indexFidem_priorizados(Request $request)
    {
        // Verifica si la solicitud es AJAX
        if ($request->ajax()) {
            // Obtiene los datos de la base de datos
           
            
                     
           $datas = Fidemcontigo::select('fidemcontigos.*')
                    ->where('fidemcontigos.estado', 'Activo')
                    ->join('evoluciones', 'evoluciones.fidemcontigos_id', '=', 'fidemcontigos.id')
                    ->join('seguimientos', 'seguimientos.evoluciones_id', '=', 'evoluciones.id')
                    ->whereColumn('evoluciones.id_evolucion', 'fidemcontigos.id_evolucion')
                    ->where('seguimientos.estado_contacto', 'si')
                    ->where('seguimientos.priorizado', 'priorizado')
                    ->where('seguimientos.created_at', function ($query) {
                        $query->selectRaw('MAX(s2.created_at)')
                            ->from('seguimientos as s2')
                            ->whereColumn('s2.evoluciones_id', 'seguimientos.evoluciones_id');
                    })
                    ->get();
              
    
    
            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="evolucion" id="' . $datas->id . '" class="seguimientoadd btn btn-float btn-sm btn-success tooltipsC" title="Adicionar seguimientos"  ><i class="fas fa-notes-medical "></i></button>' .
                    $button = '<button type="button" name="observacion" id="' . $datas->id . '" class="consultaseguimiento btn btn-float btn-sm btn-warning tooltipsC" title="Ver Seguimientos"  ><i class="fas fa-user-check"></i></button><br>';
    
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
           
        }
    
        // Si no es una solicitud AJAX, redirige a la vista principal
        //return view('paliativos.fidemcontigo.index');
    
    }
    



    
    //Método para mostrar las observaciones con blade
    public function observaciones()
    {
        $registros = Fidemcontigo::all(); // Consulta todos los registros
        return view('paliativos.fidemcontigo.index', compact('registros')); // Llama a la vista

    }



   public function buscarPaciente($documento)
{
    $paciente = DB::table('fidemcontigo')->where('documento', $documento)->first();

    if ($paciente) {
        return response()->json($paciente);
    } else {
        return response()->json(['mensaje' => 'Paciente no encontrado'], 404);
    }
}






    

    // Método para mostrar la vista del analista
    public function indexAnalista()
    {
        return view('paliativos.fidemcontigo.indexAnalista');
    }

    // Método para mostrar la vista de consulta
    public function indexConsulta()
    {
        return view('paliativos.fidemcontigo.indexConsulta');
    }

    // Método para mostrar la vista del informe
    public function indexInforme()
    {
        return view('paliativos.fidemcontigo.indexInforme');
    }



    public function index1(Request $request)
    {

 

        if ($request->ajax()) {
            
            
      $rol_id = $request->session()->get('rol_id');
      
       $user = $request->session()->get('usuario');
     
       if($rol_id == 1 || $rol_id == 2) {

        if ($request->state != '' ||  $request->profesional != '' ||$request->future1 != '' || $request->estado_pac != '') {


            $estadomax =  DB::table('estados')
            ->select(DB::raw('MAX(id_estado) as last_id_estado'))
            ->groupBy('documento');

        $estadomaxc = DB::table('estados')
            ->rightJoinSub($estadomax, 'last_ids', function ($join) {
                $join->on('estados.id_estado', '=', 'last_ids.last_id_estado');
            });
    
         $datas = DB::table('bdpaliativos')
            ->leftJoinSub($estadomaxc, 'last_ids2', function ($join) {
                $join->on('bdpaliativos.document', '=', 'last_ids2.documento');
            })
            ->selectRaw('bdpaliativos.*, last_ids2.*, TIMESTAMPDIFF(YEAR, bdpaliativos.date_birth, now()) as edad')
            ->orderBy('bdpaliativos.state');
    
    
    if($request->state != ''){

       $datas->where('bdpaliativos.state',$request->state);

    }
    
    if($request->profesional != ''){

       $datas->where('bdpaliativos.profesional',$request->profesional);

    }
    
    if($request->future1 != ''){

        $datas->where('bdpaliativos.future1',$request->future1);

    }
    
    if($request->estado_pac != ''){

       $datas->where('last_ids2.estado_pac',$request->estado_pac);

    }
    
        $datas->get();
    
        return  DataTables()->of($datas)
            ->addColumn('action', function ($datas) {
                $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                    $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>' .
                    $button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                    $button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>'
                    //$button = '<button type="button" name="editarpaciente" id="' . $datas->id . '" class="editarpaciente btn btn-float btn-sm btn-dark tooltipsC" title="Editar Paciente"  ><i class="fas fa-edit"></i></button>'
                    // . $button = '<button type="button" name="seguimiento" class="seguimientoadd btn btn-app bg-danger tooltipsC" title="Add seguimiento" value="' . $datas->id . '" ><span class="badge bg-teal">Seguimiento</span><i class="fas fa-laptop-medical"></i> Seguimiento </button>'
                ;

                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);

            }else{


            $estadomax =  DB::table('estados')
            ->select(DB::raw('MAX(id_estado) as last_id_estado'))
            ->groupBy('documento');

        $estadomaxc = DB::table('estados')
            ->rightJoinSub($estadomax, 'last_ids', function ($join) {
                $join->on('estados.id_estado', '=', 'last_ids.last_id_estado');
            });



        $datas = DB::table('bdpaliativos')
            ->leftJoinSub($estadomaxc, 'last_ids2', function ($join) {
                $join->on('bdpaliativos.document', '=', 'last_ids2.documento');
            })->selectRaw('bdpaliativos.*, last_ids2.*, TIMESTAMPDIFF(YEAR, bdpaliativos.date_birth, now()) as edad')->orderBy('bdpaliativos.state')->get();


        return  DataTables()->of($datas)
            ->addColumn('action', function ($datas) {
                $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                    $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>' .
                    $button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                    $button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>'
                    //$button = '<button type="button" name="editarpaciente" id="' . $datas->id . '" class="editarpaciente btn btn-float btn-sm btn-dark tooltipsC" title="Editar Paciente"  ><i class="fas fa-edit"></i></button>'
                    // . $button = '<button type="button" name="seguimiento" class="seguimientoadd btn btn-app bg-danger tooltipsC" title="Add seguimiento" value="' . $datas->id . '" ><span class="badge bg-teal">Seguimiento</span><i class="fas fa-laptop-medical"></i> Seguimiento </button>'
                ;

                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);


            }
            
       }else if($rol_id == 3) {
           
           if ($request->state != '' ||  $request->profesional != '' ||$request->future1 != '' || $request->estado_pac != '') {


            $estadomax =  DB::table('estados')
            ->select(DB::raw('MAX(id_estado) as last_id_estado'))
            ->groupBy('documento');

        $estadomaxc = DB::table('estados')
            ->rightJoinSub($estadomax, 'last_ids', function ($join) {
                $join->on('estados.id_estado', '=', 'last_ids.last_id_estado');
            });
    
         $datas = DB::table('bdpaliativos')
            ->leftJoinSub($estadomaxc, 'last_ids2', function ($join) {
                $join->on('bdpaliativos.document', '=', 'last_ids2.documento');
            })
            ->selectRaw('bdpaliativos.*, last_ids2.*, TIMESTAMPDIFF(YEAR, bdpaliativos.date_birth, now()) as edad')
            ->orderBy('bdpaliativos.state');
    
    
    if($request->state != ''){

       $datas->where('bdpaliativos.state',$request->state);

    }
    
    if($request->profesional != ''){

       $datas->where('bdpaliativos.profesional',$request->profesional);

    }
    
    if($request->future1 != ''){

        $datas->where('bdpaliativos.future1',$request->future1);

    }
    
    if($request->estado_pac != ''){

       $datas->where('last_ids2.estado_pac',$request->estado_pac);

    }
    
        $datas->get();


        return  DataTables()->of($datas)
            ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                    $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>' .
                    $button = '<div id="ocultarid"><button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button></div>'
                    // . $button = '<button type="button" name="agendar" class="agenda btn btn-app bg-warning tooltipsC" title="Clic para agendar" value="' . $datas->id . '" ><span class="badge bg-teal">Psico</span><i class="fas fa-file-medical"></i> Agendar </button>'
                    // . $button = '<button type="button" name="seguimiento" class="seguimientoadd btn btn-app bg-danger tooltipsC" title="Add seguimiento" value="' . $datas->id . '" ><span class="badge bg-teal">Seguimiento</span><i class="fas fa-laptop-medical"></i> Seguimiento </button>'
                ;

                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);

            }else{


            $estadomax =  DB::table('estados')
            ->select(DB::raw('MAX(id_estado) as last_id_estado'))
            ->groupBy('documento');

        $estadomaxc = DB::table('estados')
            ->rightJoinSub($estadomax, 'last_ids', function ($join) {
                $join->on('estados.id_estado', '=', 'last_ids.last_id_estado');
            });



        $datas = DB::table('bdpaliativos')
            ->leftJoinSub($estadomaxc, 'last_ids2', function ($join) {
                $join->on('bdpaliativos.document', '=', 'last_ids2.documento');
            })
            ->selectRaw('bdpaliativos.*, last_ids2.*, TIMESTAMPDIFF(YEAR, bdpaliativos.date_birth, now()) as edad')
            ->orderBy('bdpaliativos.state')->get();


        return  DataTables()->of($datas)
            ->addColumn('action', function ($datas) {
                $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                    $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>'.
                    $button = '<div id="ocultarid"><button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button></div>'
                   // $button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                    //$button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>'
                    // . $button = '<button type="button" name="agendar" class="agenda btn btn-app bg-warning tooltipsC" title="Clic para agendar" value="' . $datas->id . '" ><span class="badge bg-teal">Psico</span><i class="fas fa-file-medical"></i> Agendar </button>'
                    // . $button = '<button type="button" name="seguimiento" class="seguimientoadd btn btn-app bg-danger tooltipsC" title="Add seguimiento" value="' . $datas->id . '" ><span class="badge bg-teal">Seguimiento</span><i class="fas fa-laptop-medical"></i> Seguimiento </button>'
                ;

                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);


            }
           
           
           

        }
        
        }


        return view('paliativos.index');
    }
  
}




    






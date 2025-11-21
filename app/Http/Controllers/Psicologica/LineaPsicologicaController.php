<?php

namespace App\Http\Controllers\Psicologica;

use App\Http\Controllers\Controller;
use App\Models\Psicologica\LineaPsicologica;
use App\Models\Seguridad\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Psicologica\ObservacionesPsicologia;


class LineaPsicologicaController extends Controller
{
    
    
    public function index( )
    {
     return view('lineaPsicologica.index');
    }
    
    
    public function indexProcedimiento( )
    {
     return view('lineaPsicologica.index');
    }
    
    public function index1(Request $request)
    {

        if ($request->ajax()) {

            $usuario_id = $request->session()->get('usuario_id');

            $datas = DB::table('psicologica')
                ->join('usuario', 'psicologica.user_id', '=', 'usuario.id')
                ->select(
                    'psicologica.id',
                    'psicologica.surname',
                    'psicologica.ssurname',
                    'psicologica.fname',
                    'psicologica.sname',
                    'psicologica.type_document',
                    'psicologica.document',
                    'psicologica.date_birth',
                    'psicologica.municipality',
                    'psicologica.other',
                    'psicologica.address',
                    'psicologica.celular',
                    'psicologica.phone',
                    'psicologica.email',
                    'psicologica.sex',
                    'psicologica.eapb',
                    'psicologica.reason_consultation',
                    'psicologica.consultation',
                    'psicologica.diagnosis',
                    'psicologica.user_id',
                    'psicologica.created_at'
                )
                ->where([
                    ['psicologica.user_id', $usuario_id],
                    ['psicologica.future3', null]
                ])
                ->orderBy('psicologica.created_at')
                ->get();
            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="resumen" id="' . $datas->id . '" class="resumen btn btn-app bg-success tooltipsC" title="Resumen de evolucion"  ><span class="badge bg-teal">Evolución</span><i class="fas fa-notes-medical"></i> Detalle </button>'.
                     '<button type="button" name="anular" id="' . $datas->id . '" class="anular btn btn-app bg-danger tooltipsC" title="anular evolución"  ><span class="badge bg-teal">Anular</span><i class="fas fa-power-off"></i> Anular </button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('lineaPsicologica.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = array(
            'surname' => 'required',
            'fname' => 'required',
            'type_document' => 'required',
            'document' => 'numeric|required|min:9999|max:99999999999',
            'date_birth' => 'required',
            'municipality' => 'required',
            'address' => 'required',
            'celular' => 'required|max:100',
            'phone' => 'required|max:100',
            'sex' => 'required',
            'eapb' => 'required',
            'reason_consultation' => 'required',
            'consultation' => 'required',
            'future2' => 'required',
            'future4' => 'required'

        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        LineaPsicologica::create($request->all());

        return response()->json(['success' => 'ok']);

        //
    }

    // función para enviar los datos filtrados y pintar los botones del index de analista linea psicologica
    
     public function indexAnalista()
    {
        return view('lineaPsicologica.indexAnalista');
    }    

    public function indexAnalista1(Request $request)
    {
        
        
       
        $fechaAi = $request->fechaini;
        $fechaAf = $request->fechafin;
        
        
        $profesional = $request->profesional;
        $eps = $request->eps;


        $fecha_Actual = Carbon::now();
        $fecha_Actual = $fecha_Actual->Format('Y-m-d');

        if ($request->ajax()) {

            $usuario_id = $request->session()->get('usuario_id');
        
            
           

        if(($fechaAi == '' && $fechaAf == '' && $profesional == '' && $eps == '') || ($fechaAi == null && $fechaAf == null && $profesional == null && $eps == null) )
            
            {
        
        
            $datas = DB::table('psicologica')
                ->join('usuario', 'psicologica.user_id', '=', 'usuario.id')
                ->select(
                    'psicologica.id',
                    'psicologica.surname',
                    'psicologica.ssurname',
                    'psicologica.fname',
                    'psicologica.sname',
                    'psicologica.type_document',
                    'psicologica.document',
                    'psicologica.date_birth',
                    'psicologica.municipality',
                    'psicologica.other',
                    'psicologica.address',
                    'psicologica.celular',
                    'psicologica.phone',
                    'psicologica.email',
                    'psicologica.sex',
                    'psicologica.eapb',
                    'psicologica.reason_consultation',
                    'psicologica.consultation',
                    'psicologica.diagnosis',
                    'psicologica.user_id',
                    'psicologica.created_at'
                )
                ->where([
                    
                    ['psicologica.future5', null],
                    ['psicologica.future3', null]
                ])
                ->orderBy('psicologica.created_at')
                ->get();
           
            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="resumen" id="' . $datas->id . '" class="resumen btn btn-app bg-success tooltipsC" title="Resumen de evolucion"  ><span class="badge bg-teal">Evolución</span><i class="fas fa-notes-medical"></i> Detalle </button>'
                        . $button = '<button type="button" name="agendar" class="agenda btn btn-app bg-warning tooltipsC" title="Clic para agendar" value="' . $datas->id . '" ><span class="badge bg-teal">Psico</span><i class="fas fa-file-medical"></i> Agendar </button>'
                        . $button = '<button type="button" name="seguimiento" class="seguimientoadd btn btn-app bg-danger tooltipsC" title="Add seguimiento" value="' . $datas->id . '" ><span class="badge bg-teal">Seguimiento</span><i class="fas fa-laptop-medical"></i> Seguimiento </button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
           
            }else{
                
                  $datas = DB::table('psicologica')
                ->join('usuario', 'psicologica.user_id', '=', 'usuario.id')
                ->select(
                    'psicologica.id',
                    'psicologica.surname',
                    'psicologica.ssurname',
                    'psicologica.fname',
                    'psicologica.sname',
                    'psicologica.type_document',
                    'psicologica.document',
                    'psicologica.date_birth',
                    'psicologica.municipality',
                    'psicologica.other',
                    'psicologica.address',
                    'psicologica.celular',
                    'psicologica.phone',
                    'psicologica.email',
                    'psicologica.sex',
                    'psicologica.eapb',
                    'psicologica.reason_consultation',
                    'psicologica.consultation',
                    'psicologica.diagnosis',
                    'psicologica.user_id',
                    'psicologica.created_at'
                )
                ->where([['psicologica.future5', null],['psicologica.future3', null]])
                ->orderBy('psicologica.created_at');
                
                
                if($fechaAi != '' && $fechaAf != ''){
                     
                   $datas->whereBetween('psicologica.date_birth', [$fechaAi,$fechaAf]); 
                    
                }
                
                if(!empty($profesional)){
                    
                   
                   $datas->where('psicologica.consultation', $profesional); 
                    
                }
                
                if(!empty($eps)){
                   
                   $datas->where('psicologica.eapb', $eps); 
                    
                }
                
                $datas->get();
                
                return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="resumen" id="' . $datas->id . '" class="resumen btn btn-app bg-success tooltipsC" title="Resumen de evolucion"  ><span class="badge bg-teal">Evolución</span><i class="fas fa-notes-medical"></i> Detalle </button>'
                        . $button = '<button type="button" name="agendar" class="agenda btn btn-app bg-warning tooltipsC" title="Clic para agendar" value="' . $datas->id . '" ><span class="badge bg-teal">Psico</span><i class="fas fa-file-medical"></i> Agendar </button>'
                        . $button = '<button type="button" name="seguimiento" class="seguimientoadd btn btn-app bg-danger tooltipsC" title="Add seguimiento" value="' . $datas->id . '" ><span class="badge bg-teal">Seguimiento</span><i class="fas fa-laptop-medical"></i> Seguimiento </button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
                
                
                
                
                
                
            }
                
                
                
            
    
        }
    
    

        return view('lineaPsicologica.indexAnalista');
    }

    // función para enviar los datos filtrados y pintar los botones del index de agendado en analista
    
    
      public function indexAnalistaa()
    {
        return view('lineaPsicologica.indexAnalista');
    }    


    public function indexAnalistaa1(Request $request)
    {

        if ($request->ajax()) {

            $usuario_id = $request->session()->get('usuario_id');

            $datas = DB::table('psicologica')
                ->join('usuario', 'psicologica.user_id', '=', 'usuario.id')
                ->leftJoin('obspsicologia', 'psicologica.id', '=', 'obspsicologia.evo_id')
                ->select(
                    'psicologica.id',
                    'psicologica.surname',
                    'psicologica.ssurname',
                    'psicologica.fname',
                    'psicologica.sname',
                    'psicologica.type_document',
                    'psicologica.document',
                    'psicologica.date_birth',
                    'psicologica.municipality',
                    'psicologica.other',
                    'psicologica.address',
                    'psicologica.celular',
                    'psicologica.phone',
                    'psicologica.email',
                    'psicologica.sex',
                    'psicologica.eapb',
                    'psicologica.reason_consultation',
                    'psicologica.consultation',
                    'psicologica.diagnosis',
                    'psicologica.user_id',
                    'psicologica.created_at',
                    DB::raw("GROUP_CONCAT(obspsicologia.addobservacion SEPARATOR ' | ') as observaciones")
                )
                ->where('psicologica.future5', 1)
                ->groupBy(
                    'psicologica.id',
                    'psicologica.surname',
                    'psicologica.ssurname',
                    'psicologica.fname',
                    'psicologica.sname',
                    'psicologica.type_document',
                    'psicologica.document',
                    'psicologica.date_birth',
                    'psicologica.municipality',
                    'psicologica.other',
                    'psicologica.address',
                    'psicologica.celular',
                    'psicologica.phone',
                    'psicologica.email',
                    'psicologica.sex',
                    'psicologica.eapb',
                    'psicologica.reason_consultation',
                    'psicologica.consultation',
                    'psicologica.diagnosis',
                    'psicologica.user_id',
                    'psicologica.created_at'
                )
                ->orderBy('psicologica.created_at')
                ->get();
            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="resumen" id="' . $datas->id . '" class="resumen btn btn-app bg-success tooltipsC" title="Resumen de evolucion"  ><span class="badge bg-teal">Evolución</span><i class="fas fa-notes-medical"></i> Detalle </button>';
                    return $button;
                })
                ->addColumn('actions', function ($datas) {
                    //$button ='<button type="button" name="agendar" value="'.$datas->id.'" class="agenda btn btn-danger tooltipsC" title="Clic desagendar" ><i class="fas fa-file-medical-alt fa-2x"></i></button>';
                    $button = '<button type="button" class="agenda btn btn-app bg-danger tooltipsC" title="Clic para desagendar"  value="' . $datas->id . '" ><span class="badge bg-teal">Psico</span><i class="fas fa-brain"></i> Agendada </button>';

                    return $button;
                })
                ->rawColumns(['action', 'actions'])
                ->make(true);
        }


        return view('lineaPsicologica.indexAnalista');
    }

    // función para enviar los datos filtrados y pintar los botones del index de seguimiento en analista
    
       public function indexAnalistas()
    {
        return view('lineaPsicologica.indexAnalista');
    } 

    public function indexAnalistas1(Request $request)
    {
       
        
        if ($request->ajax()) {

            $usuario_id = $request->session()->get('usuario_id');
          
           $datas = DB::table('psicologica')
                ->join('usuario', 'psicologica.user_id', '=', 'usuario.id')
                ->select(
                    'psicologica.id',
                    'psicologica.surname',
                    'psicologica.ssurname',
                    'psicologica.fname',
                    'psicologica.sname',
                    'psicologica.type_document',
                    'psicologica.document',
                    'psicologica.date_birth',
                    'psicologica.municipality',
                    'psicologica.other',
                    'psicologica.address',
                    'psicologica.celular',
                    'psicologica.phone',
                    'psicologica.email',
                    'psicologica.sex',
                    'psicologica.eapb',
                    'psicologica.reason_consultation',
                    'psicologica.consultation',
                    'psicologica.diagnosis',
                    'psicologica.user_id',
                    'psicologica.created_at'
                )
                ->where([['psicologica.future5', 2]])
                ->orderBy('psicologica.created_at')->get();
                
                
                
                return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="observacion" class="observacion btn btn-app bg-info tooltipsC" title="Add Observación" value="' . $datas->id . '" ><span class="badge bg-teal">Add+</span><i class="fa fa-plus-circle"></i> Add Obs</button>'
                        . $button = '<button type="button" name="resumen" id="' . $datas->id . '" class="resumen btn btn-app bg-success tooltipsC" title="Resumen de evolucion"  ><span class="badge bg-teal">Evolución</span><i class="fas fa-notes-medical"></i> Detalle </button>'
                        . $button = '<button type="button" name="agendar" class="agenda btn btn-app bg-warning tooltipsC" title="Clic para agendar" value="' . $datas->id . '" ><span class="badge bg-teal">Psico</span><i class="fas fa-file-medical"></i> Agendar </button>';
                    return $button;
                })

                ->rawColumns(['action'])
                ->make(true);
                
            
                
              
        }


        return view('lineaPsicologica.indexAnalista');
    }


    public function detalleEvolucion($id)
    {
        if (request()->ajax()) {


            $data = Usuario::with('roles1')->get();
            //  $detalleEvo = DB::table('psicologica')
            //  ->rightjoin('obspsicologia', 'psicologica.id', '=', 'obspsicologia.user_id')
            //  ->where([['psicologica.id', '=', $id]])->get();

            $detalleEvo = LineaPsicologica::with('observacionadd')->findOrFail($id);


            return response()->json([['evolucion' => $detalleEvo], ['usuario' => $data]]);
        }
    }



    public function addseguimiento($id)
    {
        if (request()->ajax()) {

            $detallename = DB::table('psicologica')
                ->where('id', '=', $id)->get();

            return response()->json(['add' => $detallename]);
        }
    }


    public function agendadoEvolucion(Request $request)
            {
                if ($request->ajax()) {
                    
                    DB::beginTransaction();
                   
                    try {
                        $usuario_id = $request->session()->get('usuario_id');
                        $usuario = $request->session()->get('usuario');
                        $fecha_Actual = Carbon::now();
            
                        $agenda = new LineaPsicologica();
            
                        $datas = DB::table('psicologica')->select('future5')->where('id', $request->input('id'))->first();
            
                        foreach ($datas as $data) {
                            if ($data == null || $data == 2) {
                                $agenda->findOrFail($request->input('id'))->update([
                                    'future5' => 1
                                ]);
            
                                $comentarioadd = "Paciente asignado por  $usuario  El día  $fecha_Actual";
                                $add_asignacion = new ObservacionesPsicologia;
                                $add_asignacion->addobservacion = $comentarioadd;
                                $add_asignacion->evo_id = $request->input('id');
                                $add_asignacion->user_id = $usuario_id;
                                $add_asignacion->save();
            
                                DB::commit();
            
                                return response()->json([
                                    'respuesta' => 'Cita agendada correctamente',
                                    'titulo' => 'Control turnos',
                                    'icon' => 'success'
                                ]);
                            } else if ($data == 1) {
                                $agenda->findOrFail($request->input('id'))->update([
                                    'future5' => null
                                ]);
            
                                $comentariodadd = "Paciente Desasignado por  $usuario  El día  $fecha_Actual";
                                $add_asignacion = new ObservacionesPsicologia;
                                $add_asignacion->addobservacion = $comentariodadd;
                                $add_asignacion->evo_id = $request->input('id');
                                $add_asignacion->user_id = $usuario_id;
                                $add_asignacion->save();
            
                                DB::commit();
            
                                return response()->json([
                                    'respuesta' => 'Cita desagendada correctamente',
                                    'titulo' => 'Control turnos',
                                    'icon' => 'warning'
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return response()->json([
                            'respuesta' => 'Error al procesar la solicitud',
                            'titulo' => 'Error',
                            'icon' => 'error',
                            'error' => $e->getMessage()
                        ], 500);
                    }
                }
            }


    public function indexava(Request $request)
    {

        if ($request->ajax()) {
        }

        return view('ambienteAva.index');
    }


public function informePsico( )
    {
        return view('lineaPsicologica.indexInforme');
    }

    public function informePsico1(Request $request)
    {

        $fechaAi = $request->fechaini." 00:00:01";
        $fechaAf = $request->fechafin." 23:59:59";


        $fecha_Actual = Carbon::now();
        $fecha_Actual = $fecha_Actual->Format('Y-m-d');

        $data = Usuario::with('roles1')->get();

        if ($request->ajax()) {

            $datas = DB::table('psicologica')->join('usuario', 'psicologica.user_id', '=', 'usuario.id')
                ->select(
                    'psicologica.id',
                    'psicologica.surname',
                    'psicologica.ssurname',
                    'psicologica.fname',
                    'psicologica.sname',
                    'psicologica.type_document',
                    'psicologica.document',
                    'psicologica.date_birth',
                    'psicologica.municipality',
                    'psicologica.other',
                    'psicologica.address',
                    'psicologica.celular',
                    'psicologica.phone',
                    'psicologica.email',
                    'psicologica.sex',
                    'psicologica.eapb',
                    'psicologica.reason_consultation',
                    'psicologica.consultation',
                    'psicologica.diagnosis',
                    'usuario.usuario',
                    'psicologica.created_at'
                )
                ->whereBetween('psicologica.created_at', [$fechaAi, $fechaAf])
                ->orderBy('psicologica.created_at')
                ->get();
            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="resumen" id="' . $datas->id . '" class="resumen btn btn-app bg-success tooltipsC" title="Resumen de evolucion"  ><span class="badge bg-teal">Evolución</span><i class="fas fa-notes-medical"></i> Detalle </button>';
                             

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true)
                ;

                return response()->json(['usuario' => $data]);
        }


        return view('lineaPsicologica.indexInforme');
    }
    
    
    public function consultarDocumento(Request $request)
    {
        if (request()->ajax()) {

            
            $detallePaciente = LineaPsicologica::where('document',$request->documento)->orderBy('id','desc')->limit(1)->get();
        
            
            if($detallePaciente->isEmpty())
            
            return response()->json(['data' => 'vacio']);
            else
            return response()->json(['data' => $detallePaciente]);
        }
    }
    
    public function anularEvolucion(Request $request, $id)
    {
       
        
        if ($request->ajax()) {
            
            // Obtener la fecha y hora actuales
        $currentDateTime = now();

            

                $order = DB::table('psicologica')->select('future3','created_at')->where('id',$id)->first();



            // Verificar si la orden existe
                    if ($order) {
                        // Calcular la diferencia en horas entre la fecha de creación y la fecha actual
                        $hoursDifference = $currentDateTime->diffInHours($order->created_at);
            
                        // Verificar si la diferencia es menor o igual a 24 horas
                        if ($hoursDifference <= 24) {
                            // Si future3 es nulo o está vacío, se procede con la anulación
                            if (empty($order->future3)) {
                                // Actualizar el campo 'future3' a 'NO'
                                LineaPsicologica::findOrFail($id)->update([
                                    'future3' => 'NO'
                                ]);
            
                                return response()->json([
                                    'respuesta' => 'Evolución desactivada',
                                    'titulo' => 'System Fidem',
                                    'icon' => 'warning'
                                ]);
                            } else {
                                return response()->json([
                                    'respuesta' => 'La orden no puede ser anulada pasaron 24 Horas',
                                    'titulo' => 'System Fidem',
                                    'icon' => 'error'
                                ]);
                            }
                        } else {
                            // Si han pasado más de 24 horas, no se permite la anulación
                            return response()->json([
                                'respuesta' => 'No se puede anular la orden. Han pasado más de 24 horas.',
                                'titulo' => 'System Fidem',
                                'icon' => 'error'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'respuesta' => 'Orden no encontrada',
                            'titulo' => 'System Fidem',
                            'icon' => 'error'
                        ]);
                    }
                }
    }
}

<?php

namespace App\Http\Controllers\EncuestaFisiatria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EncuestaFisiatria\EncuestaFisiatria;
use App\Models\EncuestaFisiatria\ObservacionesFisiatria;
use App\Models\Seguridad\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

class EncuestaFisiatriaController extends Controller
{
    public function index()
    {
        return view('fisiatria.index');
    }


    /**
     * Listar encuestas con DataTables
     */
    public function index1(Request $request)
    {
        if ($request->ajax()) {
            $usuario_id = $request->session()->get('usuario_id');
            
            $datas = DB::table('encuestasfisiatria')
                ->join('usuario', 'encuestasfisiatria.user_id', '=', 'usuario.id')
                ->select(
                    'encuestasfisiatria.id',
                    'encuestasfisiatria.surname',
                    'encuestasfisiatria.ssurname',
                    'encuestasfisiatria.fname',
                    'encuestasfisiatria.sname',
                    'encuestasfisiatria.type_document',
                    'encuestasfisiatria.document',
                    'encuestasfisiatria.eapb',
                    'encuestasfisiatria.fecha_solicitud',
                    'encuestasfisiatria.profesional',
                    'encuestasfisiatria.dx',
                    'encuestasfisiatria.dispositivo_silla',
                    'encuestasfisiatria.dispositivo_apoyo',
                    'encuestasfisiatria.other',
                    'encuestasfisiatria.solicitud_dispositivo',
                    'encuestasfisiatria.antecedentes_dx_cancer',
                    'encuestasfisiatria.antecedentes_toxina_espasticidad',
                    'encuestasfisiatria.camilla_ambulancia',
                    'encuestasfisiatria.tipo_solicitud',
                    'encuestasfisiatria.reason_consultation',
                    'encuestasfisiatria.observacion',
                    'encuestasfisiatria.user_id',
                    'encuestasfisiatria.created_at',
                    'usuario.pnombre as usuario_nombre'
                )
                ->where([
                    ['encuestasfisiatria.user_id', $usuario_id],
                    // Agregar condición si necesitas filtrar registros activos
                    // ['encuestasfisiatria.estado', '!=', 'anulado']
                ])
                ->orderBy('encuestasfisiatria.created_at', 'desc')
                ->get();

            return DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="resumen" id="' . $datas->id . '" class="resumen btn btn-app bg-success tooltipsC" title="Resumen de solicitud fisiatría">
                                <span class="badge bg-teal">Detalle</span>
                                <i class="fas fa-notes-medical"></i> Detalle 
                              </button>' .
                              '<button type="button" name="anular" id="' . $datas->id . '" class="anular btn btn-app bg-danger tooltipsC" title="Anular solicitud fisiatría">
                                <span class="badge bg-red">Anular</span>
                                <i class="fas fa-power-off"></i> Anular 
                              </button>';
                    return $button;
                })
                ->editColumn('dispositivo_silla', function ($datas) {
                    return $datas->dispositivo_silla ?: 'N/A';
                })
                ->editColumn('dispositivo_apoyo', function ($datas) {
                    return $datas->dispositivo_apoyo ?: 'Ninguno';
                })
                ->editColumn('solicitud_dispositivo', function ($datas) {
                    if ($datas->solicitud_dispositivo == 'SI') {
                        return '<span class="badge badge-success">SI</span>';
                    } else {
                        return '<span class="badge badge-secondary">NO</span>';
                    }
                })
                ->editColumn('antecedentes_dx_cancer', function ($datas) {
                    if ($datas->antecedentes_dx_cancer == 'SI') {
                        return '<span class="badge badge-warning">SI</span>';
                    } else {
                        return '<span class="badge badge-light">NO</span>';
                    }
                })
                ->editColumn('antecedentes_toxina_espasticidad', function ($datas) {
                    if ($datas->antecedentes_toxina_espasticidad == 'SI') {
                        return '<span class="badge badge-info">SI</span>';
                    } else {
                        return '<span class="badge badge-light">NO</span>';
                    }
                })
                ->editColumn('camilla_ambulancia', function ($datas) {
                    if ($datas->camilla_ambulancia == 'SI') {
                        return '<span class="badge badge-primary">SI</span>';
                    } else {
                        return '<span class="badge badge-light">NO</span>';
                    }
                })
                ->editColumn('created_at', function ($datas) {
                    return date('Y-m-d H:i:s', strtotime($datas->created_at));
                })
                ->rawColumns(['action', 'solicitud_dispositivo', 'antecedentes_dx_cancer', 'antecedentes_toxina_espasticidad', 'camilla_ambulancia'])
                ->make(true);
        }
        
        return view('fisiatria.index');
        
       
    }

    /**
     * Guardar encuesta
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:100',
            'ssurname' => 'nullable|string|max:100',
            'fname' => 'required|string|max:100',
            'sname' => 'nullable|string|max:100',
            'type_document' => 'required|string|max:10',
            'document' => 'required|string|max:20',
            'fecha_solicitud' => 'required|date',
            'eapb' => 'required|string|max:50',
            'dx' => 'nullable|string|max:99',
            'dispositivo_silla' => 'nullable|string|max:50',
            'dispositivo_apoyo' => 'nullable|string|max:200',
            'other' => 'nullable|string|max:100',
            'solicitud_dispositivo' => 'nullable|string|max:10',
            'antecedentes_dx_cancer' => 'nullable|string|max:10',
            'antecedentes_toxina_espasticidad' => 'nullable|string|max:10',
            'camilla_ambulancia' => 'nullable|string|max:10',
            'tipo_solicitud' => 'required|string|max:50',
            'reason_consultation' => 'required|string',
            'observacion' => 'required|string',
        ]);

        $validated['user_id'] = $request->session()->get('usuario_id'); // o Auth::id() si usas Auth

        try {
            EncuestaFisiatria::create($validated);
            
            return response()->json([
                'success' => 'ok',
                'message' => 'Solicitud de fisiatría registrada correctamente.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => 'error',
                'errors' => 'Error al guardar los datos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mostrar detalle de encuesta
     */
    public function show($id)
    {
        try {
            $encuesta = EncuestaFisiatria::with('usuario')->findOrFail($id);
            
            return response()->json([
                [$encuesta->toArray()]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Registro no encontrado'
            ], 404);
        }
    }

    /**
     * Anular encuesta
     */
    public function anular($id)
    {
        try {
            $encuesta = EncuestaFisiatria::findOrFail($id);
            
            // Puedes agregar un campo 'estado' en tu modelo para manejar esto
            $encuesta->update(['future3' => 'NO']);
            // O eliminar el registro si es lo que prefieres
            //$encuesta->delete();
            
            return response()->json([
                'respuesta' => 'Solicitud de fisiatría anulada correctamente',
                'titulo' => 'Sistema Fisiatría',
                'icon' => 'success'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'respuesta' => 'Error al anular la solicitud',
                'titulo' => 'Sistema Fisiatría',
                'icon' => 'error'
            ]);
        }
    }

    /**
     * Métodos adicionales que pueden estar siendo llamados desde las rutas existentes
     */
    
    public function informePsico()
    {
        // Implementar según necesidades
        return view('fisiatria.informe');
    }
    
    public function indexProcedimiento()
    {
        // Implementar según necesidades
        return view('fisiatria.procedimiento');
    }
    
    public function indexProcedimientotable(Request $request)
    {
        // Implementar según necesidades para DataTables
        if ($request->ajax()) {
            // Lógica para tabla de procedimientos
            return response()->json([]);
        }
    }
    
    
      // función para enviar los datos filtrados y pintar los botones del index de analista linea psicologica
    
     public function indexAnalista()
    {
        return view('fisiatria.indexAnalista');
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
    
            if(($fechaAi == '' && $fechaAf == '' && $profesional == '' && $eps == '') || ($fechaAi == null && $fechaAf == null && $profesional == null && $eps == null))
            {
                $datas = DB::table('encuestasfisiatria')
                    ->join('usuario', 'encuestasfisiatria.user_id', '=', 'usuario.id')
                    ->select(
                        'encuestasfisiatria.id',
                        'encuestasfisiatria.surname',
                        'encuestasfisiatria.ssurname',
                        'encuestasfisiatria.fname',
                        'encuestasfisiatria.sname',
                        'encuestasfisiatria.type_document',
                        'encuestasfisiatria.document',
                        'encuestasfisiatria.fecha_solicitud',
                        'encuestasfisiatria.eapb',
                        'encuestasfisiatria.profesional',
                        'encuestasfisiatria.dx',
                        'encuestasfisiatria.dispositivo_silla',
                        'encuestasfisiatria.dispositivo_apoyo',
                        'encuestasfisiatria.other',
                        'encuestasfisiatria.solicitud_dispositivo',
                        'encuestasfisiatria.antecedentes_dx_cancer',
                        'encuestasfisiatria.antecedentes_toxina_espasticidad',
                        'encuestasfisiatria.camilla_ambulancia',
                        'encuestasfisiatria.tipo_solicitud',
                        'encuestasfisiatria.reason_consultation',
                        'encuestasfisiatria.observacion',
                        'encuestasfisiatria.user_id',
                        'encuestasfisiatria.created_at'
                    )
                    ->where([
                        ['encuestasfisiatria.future5', null],
                        ['encuestasfisiatria.future3', null]
                    ])
                    ->orderBy('encuestasfisiatria.created_at')
                    ->get();
           
                return  DataTables()->of($datas)
                    
                    ->addColumn('action', function ($datas) {
                        $button = '
                        <div class="btn-group-ios" role="group">
                            <button type="button" 
                                    name="resumen" 
                                    id="' . $datas->id . '" 
                                    class="resumen btn-ios btn-ios-success tooltipsC" 
                                    title="Ver detalle"
                                    data-toggle="tooltip">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" 
                                    name="agendar" 
                                    class="agenda btn-ios btn-ios-warning tooltipsC" 
                                    title="Agendar cita" 
                                    value="' . $datas->id . '"
                                    data-toggle="tooltip">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                            <button type="button" 
                                    name="seguimiento" 
                                    class="seguimientoadd btn-ios btn-ios-info tooltipsC" 
                                    title="Seguimiento" 
                                    value="' . $datas->id . '"
                                    data-toggle="tooltip">
                                <i class="fas fa-clipboard-check"></i>
                            </button>
                        </div>';
                    
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
           
            }else{
                
                $datas = DB::table('encuestasfisiatria')
                    ->join('usuario', 'encuestasfisiatria.user_id', '=', 'usuario.id')
                    ->select(
                        'encuestasfisiatria.id',
                        'encuestasfisiatria.surname',
                        'encuestasfisiatria.ssurname',
                        'encuestasfisiatria.fname',
                        'encuestasfisiatria.sname',
                        'encuestasfisiatria.type_document',
                        'encuestasfisiatria.document',
                        'encuestasfisiatria.fecha_solicitud',
                        'encuestasfisiatria.eapb',
                        'encuestasfisiatria.profesional',
                        'encuestasfisiatria.dx',
                        'encuestasfisiatria.dispositivo_silla',
                        'encuestasfisiatria.dispositivo_apoyo',
                        'encuestasfisiatria.other',
                        'encuestasfisiatria.solicitud_dispositivo',
                        'encuestasfisiatria.antecedentes_dx_cancer',
                        'encuestasfisiatria.antecedentes_toxina_espasticidad',
                        'encuestasfisiatria.camilla_ambulancia',
                        'encuestasfisiatria.tipo_solicitud',
                        'encuestasfisiatria.reason_consultation',
                        'encuestasfisiatria.observacion',
                        'encuestasfisiatria.user_id',
                        'encuestasfisiatria.created_at'
                    )
                    ->where([['encuestasfisiatria.future5', null],['encuestasfisiatria.future3', null]])
                    ->orderBy('encuestasfisiatria.created_at');
                
                if($fechaAi != '' && $fechaAf != ''){
                   $datas->whereBetween('encuestasfisiatria.fecha_solicitud', [$fechaAi,$fechaAf]); 
                }
                
                if(!empty($profesional)){
                   $datas->where('encuestasfisiatria.profesional', $profesional); 
                }
                
                if(!empty($eps)){
                   $datas->where('encuestasfisiatria.eapb', $eps); 
                }
                
                $datas->get();
                
                return  DataTables()->of($datas)
                    ->addColumn('action', function ($datas) {
                        $button = '
                        <div class="btn-group-ios" role="group">
                            <button type="button" 
                                    name="resumen" 
                                    id="' . $datas->id . '" 
                                    class="resumen btn-ios btn-ios-success tooltipsC" 
                                    title="Ver detalle"
                                    data-toggle="tooltip">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" 
                                    name="agendar" 
                                    class="agenda btn-ios btn-ios-warning tooltipsC" 
                                    title="Agendar cita" 
                                    value="' . $datas->id . '"
                                    data-toggle="tooltip">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                            <button type="button" 
                                    name="seguimiento" 
                                    class="seguimientoadd btn-ios btn-ios-info tooltipsC" 
                                    title="Seguimiento" 
                                    value="' . $datas->id . '"
                                    data-toggle="tooltip">
                                <i class="fas fa-clipboard-check"></i>
                            </button>
                        </div>';
                    
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
    
        return view('fisiatria.indexAnalista');
    }
    
    // función para enviar los datos filtrados y pintar los botones del index de agendado en analista
    
    public function indexAnalistaa()
    {
        return view('fisiatria.indexAnalista');
    }    
    
    public function indexAnalistaa1(Request $request)
    {
        if ($request->ajax()) {
    
            $usuario_id = $request->session()->get('usuario_id');
    
            $datas = DB::table('encuestasfisiatria')
                ->join('usuario', 'encuestasfisiatria.user_id', '=', 'usuario.id')
                ->leftJoin('observacion_encuestas_fisiatria', 'encuestasfisiatria.id', '=', 'observacion_encuestas_fisiatria.enc_id')
                ->select(
                    'encuestasfisiatria.id',
                    'encuestasfisiatria.surname',
                    'encuestasfisiatria.ssurname',
                    'encuestasfisiatria.fname',
                    'encuestasfisiatria.sname',
                    'encuestasfisiatria.type_document',
                    'encuestasfisiatria.document',
                    'encuestasfisiatria.fecha_solicitud',
                    'encuestasfisiatria.eapb',
                    'encuestasfisiatria.profesional',
                    'encuestasfisiatria.dx',
                    'encuestasfisiatria.dispositivo_silla',
                    'encuestasfisiatria.dispositivo_apoyo',
                    'encuestasfisiatria.other',
                    'encuestasfisiatria.solicitud_dispositivo',
                    'encuestasfisiatria.antecedentes_dx_cancer',
                    'encuestasfisiatria.antecedentes_toxina_espasticidad',
                    'encuestasfisiatria.camilla_ambulancia',
                    'encuestasfisiatria.tipo_solicitud',
                    'encuestasfisiatria.reason_consultation',
                    'encuestasfisiatria.observacion',
                    'encuestasfisiatria.user_id',
                    'encuestasfisiatria.created_at',
                    DB::raw("GROUP_CONCAT(observacion_encuestas_fisiatria.addobservacion SEPARATOR ' | ') as observaciones")
                )
                ->where('encuestasfisiatria.future5', 1)
                ->groupBy(
                    'encuestasfisiatria.id',
                    'encuestasfisiatria.surname',
                    'encuestasfisiatria.ssurname',
                    'encuestasfisiatria.fname',
                    'encuestasfisiatria.sname',
                    'encuestasfisiatria.type_document',
                    'encuestasfisiatria.document',
                    'encuestasfisiatria.fecha_solicitud',
                    'encuestasfisiatria.eapb',
                    'encuestasfisiatria.profesional',
                    'encuestasfisiatria.dx',
                    'encuestasfisiatria.dispositivo_silla',
                    'encuestasfisiatria.dispositivo_apoyo',
                    'encuestasfisiatria.other',
                    'encuestasfisiatria.solicitud_dispositivo',
                    'encuestasfisiatria.antecedentes_dx_cancer',
                    'encuestasfisiatria.antecedentes_toxina_espasticidad',
                    'encuestasfisiatria.camilla_ambulancia',
                    'encuestasfisiatria.tipo_solicitud',
                    'encuestasfisiatria.reason_consultation',
                    'encuestasfisiatria.observacion',
                    'encuestasfisiatria.user_id',
                    'encuestasfisiatria.created_at'
                )
                ->orderBy('encuestasfisiatria.created_at')
                ->get();
                
            return  DataTables()->of($datas)
            
            
            
                ->addColumn('action', function ($datas) {
                    $button = '<div class="btn-group-ios" role="group">
                            <button type="button" 
                                    name="resumen" 
                                    id="' . $datas->id . '" 
                                    class="resumen btn-ios btn-ios-success tooltipsC" 
                                    title="Ver detalle"
                                    data-toggle="tooltip">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" 
                                    name="agendar" 
                                    class="agenda btn-ios btn-ios-warning tooltipsC" 
                                    title="Desagendar cita" 
                                    value="' . $datas->id . '"
                                    data-toggle="tooltip">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                            
                        </div>';
                    return $button;
                })
               
                ->rawColumns(['action', 'actions'])
                ->make(true);
        }
    
        return view('fisiatria.indexAnalista');
    }
    
    // función para enviar los datos filtrados y pintar los botones del index de seguimiento en analista
    
    public function indexAnalistas()
    {
        return view('fisiatria.indexAnalista');
    } 
    
    public function indexAnalistas1(Request $request)
    {
        if ($request->ajax()) {
    
            $usuario_id = $request->session()->get('usuario_id');
          
            $datas = DB::table('encuestasfisiatria')
                ->join('usuario', 'encuestasfisiatria.user_id', '=', 'usuario.id')
                ->select(
                    'encuestasfisiatria.id',
                    'encuestasfisiatria.surname',
                    'encuestasfisiatria.ssurname',
                    'encuestasfisiatria.fname',
                    'encuestasfisiatria.sname',
                    'encuestasfisiatria.type_document',
                    'encuestasfisiatria.document',
                    'encuestasfisiatria.fecha_solicitud',
                    'encuestasfisiatria.eapb',
                    'encuestasfisiatria.profesional',
                    'encuestasfisiatria.dx',
                    'encuestasfisiatria.dispositivo_silla',
                    'encuestasfisiatria.dispositivo_apoyo',
                    'encuestasfisiatria.other',
                    'encuestasfisiatria.solicitud_dispositivo',
                    'encuestasfisiatria.antecedentes_dx_cancer',
                    'encuestasfisiatria.antecedentes_toxina_espasticidad',
                    'encuestasfisiatria.camilla_ambulancia',
                    'encuestasfisiatria.tipo_solicitud',
                    'encuestasfisiatria.reason_consultation',
                    'encuestasfisiatria.observacion',
                    'encuestasfisiatria.user_id',
                    'encuestasfisiatria.created_at'
                )
                ->where([['encuestasfisiatria.future5', 2]])
                ->orderBy('encuestasfisiatria.created_at')->get();
                
            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="observacion" class="observacion btn btn-app bg-info tooltipsC" title="Add Observación" value="' . $datas->id . '" ><span class="badge bg-teal">Add+</span><i class="fa fa-plus-circle"></i> Add Obs</button>'
                        . '<button type="button" name="resumen" id="' . $datas->id . '" class="resumen btn btn-app bg-success tooltipsC" title="Resumen de fisiatria"  ><span class="badge bg-teal">Evolución</span><i class="fas fa-notes-medical"></i> Detalle </button>'
                        . '<button type="button" name="agendar" class="agenda btn btn-app bg-warning tooltipsC" title="Clic para agendar" value="' . $datas->id . '" ><span class="badge bg-teal">Fisiatria</span><i class="fas fa-file-medical"></i> Agendar </button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('fisiatria.indexAnalista');
    }
    
    public function detalleEvolucion($id)
    {
        if (request()->ajax()) {
    
            $data = Usuario::with('roles1')->get();
            
            $detalleEvo = EncuestaFisiatria::with('observacionadd')->findOrFail($id);
    
            return response()->json([['evolucion' => $detalleEvo], ['usuario' => $data]]);
        }
    }
    
    public function addseguimiento($id)
    {
        if (request()->ajax()) {
    
            $detallename = DB::table('encuestasfisiatria')
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
                $profesional = $request->input('profesional');
    
                $agenda = new EncuestaFisiatria();
    
                $datas = DB::table('encuestasfisiatria')->select('future5')->where('id', $request->input('id'))->first();
    
                foreach ($datas as $data) {
                    if ($data == null || $data == 2) {
                        $agenda->findOrFail($request->input('id'))->update([
                            'future5' => 1,
                            'profesional' => $profesional
                        ]);
    
                        $comentarioadd = "Paciente asignado por  $usuario  El día  $fecha_Actual con el profesional: $profesional";
                        
                        // Aquí necesitarás crear el modelo ObservacionesFisiatria
                        $add_asignacion = new ObservacionesFisiatria;
                        $add_asignacion->addobservacion = $comentarioadd;
                        $add_asignacion->enc_id = $request->input('id');
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
                            'future5' => null,
                            'profesional' => null
                        ]);
    
                        $comentariodadd = "Paciente Desasignado por  $usuario  El día  $fecha_Actual";
                        $add_asignacion = new ObservacionesFisiatria;
                        $add_asignacion->addobservacion = $comentariodadd;
                        $add_asignacion->enc_id = $request->input('id');
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
    
    public function anularEvolucion(Request $request, $id)
    {
        if ($request->ajax()) {
            
            // Obtener la fecha y hora actuales
            $currentDateTime = now();
    
            $order = DB::table('encuestasfisiatria')->select('future3','created_at')->where('id',$id)->first();
    
            // Verificar si la orden existe
            if ($order) {
                // Calcular la diferencia en horas entre la fecha de creación y la fecha actual
                $hoursDifference = $currentDateTime->diffInHours($order->created_at);
    
                // Verificar si la diferencia es menor o igual a 24 horas
                if ($hoursDifference <= 24) {
                    // Si future3 es nulo o está vacío, se procede con la anulación
                    if (empty($order->future3)) {
                        // Actualizar el campo 'future3' a 'NO'
                        EncuestaFisiatria::findOrFail($id)->update([
                            'future3' => 'NO'
                        ]);
    
                        return response()->json([
                            'respuesta' => 'Encuesta fisiatría desactivada',
                            'titulo' => 'System Fidem',
                            'icon' => 'warning'
                        ]);
                    } else {
                        return response()->json([
                            'respuesta' => 'La encuesta no puede ser anulada pasaron 24 Horas',
                            'titulo' => 'System Fidem',
                            'icon' => 'error'
                        ]);
                    }
                } else {
                    // Si han pasado más de 24 horas, no se permite la anulación
                    return response()->json([
                        'respuesta' => 'No se puede anular la encuesta. Han pasado más de 24 horas.',
                        'titulo' => 'System Fidem',
                        'icon' => 'error'
                    ]);
                }
            } else {
                return response()->json([
                    'respuesta' => 'Encuesta no encontrada',
                    'titulo' => 'System Fidem',
                    'icon' => 'error'
                ]);
            }
        }
    }
    
    public function consultarDocumento(Request $request)
    {
        if (request()->ajax()) {
            
            $detallePaciente = EncuestaFisiatria::where('document',$request->documento)->orderBy('id','desc')->limit(1)->get();
        
            if($detallePaciente->isEmpty())
            
            return response()->json(['data' => 'vacio']);
            else
            return response()->json(['data' => $detallePaciente]);
        }
    }
}

<?php

namespace App\Http\Controllers\Paliativos;

use App\Http\Controllers\Controller;
use App\Imports\Pacientesimport;
use App\Imports\AmbitoImport;
use App\Models\Paliativos\BasePaliativos;
use App\Models\Paliativos\ObsPaliativos;
use App\Models\Paliativos\Estados;
use App\Models\Seguridad\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;


class BasePaliativosController extends Controller
{
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
     public function index()
    {
        
         return view('paliativos.index');
        
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
    
    public function indexa(Request $request)
    {


        if ($request->ajax()) {
            
            
      $rol_id = $request->session()->get('rol_id');
      
       $user = $request->session()->get('usuario');
     
      if($rol_id == 3) {
           
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
            })->selectRaw('bdpaliativos.*, last_ids2.*, TIMESTAMPDIFF(YEAR, bdpaliativos.date_birth, now()) as edad')
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
    
        $datas->where('bdpaliativos.profesional',$user)->whereIn('state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO'])->get();


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
            })->selectRaw('bdpaliativos.*, last_ids2.*, TIMESTAMPDIFF(YEAR, bdpaliativos.date_birth, now()) as edad')
            ->where('bdpaliativos.profesional',$user)->whereIn('state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO'])->orderBy('bdpaliativos.state')->get();


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


        
    }

    public function indexsin(Request $request)
    {


        if ($request->ajax()) {
            
             $rol_id = $request->session()->get('rol_id');

            if($rol_id == 1 || $rol_id == 2){
            
            $datas = BasePaliativos::where('state', 'SIN CONTACTO')->orderBy('id')->get();

            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                        $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>' .
                        $button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                        $button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        }

        return view('paliativos.index');
    }

    public function indexdomi(Request $request)
    {


        if ($request->ajax()) {
            
             $rol_id = $request->session()->get('rol_id');
               $user = $request->session()->get('usuario');

            if($rol_id == 1 || $rol_id == 2){

            $datas = BasePaliativos::where('type', 'DOMICILIARIO')->whereIn('state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO'])->orderBy('id')->get();

            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                        $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>' .
                        $button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                        $button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
                
            }else if($rol_id == 3){
                 $datas = BasePaliativos::where('type', 'DOMICILIARIO')->whereIn('state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO'])->orderBy('id')->get();

            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                        $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>' ;
                        //$button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                        //$button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
                
            }
        }


        return view('paliativos.index');
    }


    public function indexupe(Request $request)
    {


        if ($request->ajax()) {
            
             $rol_id = $request->session()->get('rol_id');


            $fechaini = new Carbon(now());
            $fechaini = $fechaini->toDateString();

             if($rol_id == 1 || $rol_id == 2){

            $datas = BasePaliativos::join('cosultaspes', 'bdpaliativos.document', '=', 'cosultaspes.documento')->whereIn('bdpaliativos.state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO', 'PACIENTE NO ACEPTA CITA'])
                ->select(
                    'bdpaliativos.surname',
                    'bdpaliativos.ssurname',
                    'bdpaliativos.fname',
                    'bdpaliativos.sname',
                    'bdpaliativos.type_document',
                    'bdpaliativos.document',
                    DB::raw('DATEDIFF(now(), cosultaspes.paliativista1) as diasp1'),
                    'cosultaspes.paliativista1 as paliativista1',
                    DB::raw('DATEDIFF(now(), cosultaspes.paliativista2) as diasp2'),
                    'cosultaspes.paliativista2 as paliativista2',
                    DB::raw('DATEDIFF(now(), cosultaspes.paliativista3) as diasp3'),
                    'cosultaspes.paliativista3 as paliativista3',
                    DB::raw('DATEDIFF(now(), cosultaspes.paliativista4) as diasp4'),
                    'cosultaspes.paliativista4 as paliativista4',
                   
                    DB::raw('DATEDIFF(now(), cosultaspes.experto1) as diase1'),
                    'cosultaspes.experto1 as experto1',
                    
                    DB::raw('DATEDIFF(now(), cosultaspes.experto3) as diase3'),
                    'cosultaspes.experto3 as experto3',
                    
                    DB::raw('DATEDIFF(now(), cosultaspes.experto4) as diase4'),
                    'cosultaspes.experto4 as experto4',
                    
                    // Medico no está en fidem
        
                    DB::raw('DATEDIFF(now(), cosultaspes.experto2) as diase2'),
                    'cosultaspes.experto2 as experto2',
                    'bdpaliativos.state',
                    'bdpaliativos.type',
                    'bdpaliativos.id as id'
                )
                ->orderBy('bdpaliativos.id')->get();


            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                        $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>' .
                        $button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                        $button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        }

        return view('paliativos.index');
    }
    
     public function indexupef(Request $request)
    {
        

        if ($request->ajax()) {

 $rol_id = $request->session()->get('rol_id');
 
            $fechaini = new Carbon(now());
            $fechaini = $fechaini->toDateString();

 if($rol_id == 1 || $rol_id == 2){

            $datas1 = DB::table('bdpaliativos')->join('cosultaspes', 'bdpaliativos.document', '=', 'cosultaspes.documento')->whereIn('bdpaliativos.state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO', 'PACIENTE NO ACEPTA CITA'])
                ->select(
                    'bdpaliativos.document',
                    DB::raw('DATEDIFF(now(), cosultaspes.paliativista1) as diasp1'),
                    'cosultaspes.paliativista1 as paliativista1',
                    DB::raw('DATEDIFF(now(), cosultaspes.paliativista2) as diasp2'),
                    'cosultaspes.paliativista2 as paliativista2',
                    DB::raw('DATEDIFF(now(), cosultaspes.paliativista3) as diasp3'),
                    'cosultaspes.paliativista3 as paliativista3',
                    DB::raw('DATEDIFF(now(), cosultaspes.paliativista4) as diasp4'),
                    'cosultaspes.paliativista4 as paliativista4',
                    DB::raw('DATEDIFF(now(), cosultaspes.experto1) as diase1'),
                    'cosultaspes.experto1 as experto1',
                    DB::raw('DATEDIFF(now(), cosultaspes.experto3) as diase3'),
                    'cosultaspes.experto3 as experto3',
                   
                   
                   // Nuevo medico Pedro
                    DB::raw('DATEDIFF(now(), cosultaspes.experto4) as diase4'),
                    'cosultaspes.experto4 as experto4',
                    
                    
                    
                    DB::raw('DATEDIFF(now(), cosultaspes.experto2) as diase2'),
                    'cosultaspes.experto2 as experto2'
                );



                $datas = DB::table('bdpaliativos')
                ->JoinSub($datas1, 'paliativos', function ($join) {
                $join->on('bdpaliativos.document', '=', 'paliativos.document');
                })->where(function ($query) {
                    $query->where('paliativos.diasp1', '>', 30)
                          ->orWhere('paliativos.diasp1', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diasp2', '>', 30)
                          ->orWhere('paliativos.diasp2', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diasp3', '>', 30)
                          ->orWhere('paliativos.diasp3', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diasp4', '>', 30)
                          ->orWhere('paliativos.diasp4', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diase1', '>', 30)
                          ->orWhere('paliativos.diase1', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diase3', '>', 30)
                          ->orWhere('paliativos.diase3', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diase4', '>', 30)
                          ->orWhere('paliativos.diase4', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diase2', '>', 30)
                          ->orWhere('paliativos.diase2', '=', null);
                })->orderBy('bdpaliativos.state')->get();


            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                        $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>' .
                        $button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                        $button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        }


    }
    
        public function indexue(Request $request)
    {


        if ($request->ajax()) {
            
             $rol_id = $request->session()->get('rol_id');


            $fechaini = new Carbon(now());
            $fechaini = $fechaini->toDateString();

             if($rol_id == 1 || $rol_id == 2){

            $datas1 = BasePaliativos::join('cosultaspes', 'bdpaliativos.document', '=', 'cosultaspes.documento')->whereIn('bdpaliativos.state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO', 'PACIENTE NO ACEPTA CITA'])
                ->select(
                    'bdpaliativos.surname',
                    'bdpaliativos.ssurname',
                    'bdpaliativos.fname',
                    'bdpaliativos.sname',
                    'bdpaliativos.type_document',
                    'bdpaliativos.document',
                    DB::raw('DATEDIFF(now(), cosultaspes.experto1) as diase1'),
                    'cosultaspes.experto1 as experto1',
                    DB::raw('DATEDIFF(now(), cosultaspes.experto3) as diase3'),
                    'cosultaspes.experto3 as experto3',
                    DB::raw('DATEDIFF(now(), cosultaspes.experto4) as diase4'),
                    'cosultaspes.experto4 as experto4',
                    DB::raw('DATEDIFF(now(), cosultaspes.experto2) as diase2'),
                    'cosultaspes.experto2 as experto2',
                    'bdpaliativos.state',
                    'bdpaliativos.type',
                    'bdpaliativos.id as id'
                );
                
               $datas = DB::table('bdpaliativos')
                ->JoinSub($datas1, 'paliativos', function ($join) {
                $join->on('bdpaliativos.document', '=', 'paliativos.document');
                })->where(function ($query) {
                    $query->where('paliativos.diase1', '>', 30)
                          ->orWhere('paliativos.diase1', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diase3', '>', 30)
                          ->orWhere('paliativos.diase3', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diase4', '>', 30)
                          ->orWhere('paliativos.diase4', '=', null);
                })->where(function ($query) {
                    $query->where('paliativos.diase2', '>', 30)
                          ->orWhere('paliativos.diase2', '=', null);
                })->orderBy('bdpaliativos.state')->get();        
                


            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                        $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>' .
                        $button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                        $button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        }

        return view('paliativos.index');
    }

 public function indexua(Request $request)
    {


        if ($request->ajax()) {

            $rol_id = $request->session()->get('rol_id');
             $user = $request->session()->get('usuario');

            $fechaini = new Carbon(now());
            $fechaini = $fechaini->toDateString();

         if ($rol_id == 1 ) {

            $datas1 = DB::table('bdpaliativos')->join('consulta_auxiliars', 'bdpaliativos.document', '=', 'consulta_auxiliars.documento')->whereIn('bdpaliativos.state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO', 'PACIENTE NO ACEPTA CITA'])
                ->select(
                    'bdpaliativos.document',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar1) as diasa1'),
                    'consulta_auxiliars.auxiliar1 as auxiliar1',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar2) as diasa2'),
                    'consulta_auxiliars.auxiliar2 as auxiliar2',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar3) as diasa3'),
                    'consulta_auxiliars.auxiliar3 as auxiliar3',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar4) as diasa4'),
                    'consulta_auxiliars.auxiliar4 as auxiliar4',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar5) as diasa5'),
                    'consulta_auxiliars.auxiliar5 as auxiliar5',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar6) as diasa6'),
                    'consulta_auxiliars.auxiliar6 as auxiliar6',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar7) as diasa7'),
                    'consulta_auxiliars.auxiliar7 as auxiliar7',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar8) as diasa8'),
                    'consulta_auxiliars.auxiliar8 as auxiliar8',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar9) as diasa9'),
                    'consulta_auxiliars.auxiliar9 as auxiliar9'
                );



                $datas = DB::table('bdpaliativos')
                ->JoinSub($datas1, 'auxiliares', function ($join) {
                $join->on('bdpaliativos.document', '=', 'auxiliares.document');
                })->where(function ($query) {
                    $query->where('auxiliares.diasa1', '>', 30)
                          ->orWhere('auxiliares.diasa1', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa2', '>', 30)
                          ->orWhere('auxiliares.diasa2', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa3', '>', 30)
                          ->orWhere('auxiliares.diasa3', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa4', '>', 30)
                          ->orWhere('auxiliares.diasa4', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa5', '>', 30)
                          ->orWhere('auxiliares.diasa5', '=', null);
                })
                ->where(function ($query) {
                    $query->where('auxiliares.diasa6', '>', 30)
                          ->orWhere('auxiliares.diasa6', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa7', '>', 30)
                          ->orWhere('auxiliares.diasa7', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa8', '>', 30)
                          ->orWhere('auxiliares.diasa8', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa9', '>', 30)
                          ->orWhere('auxiliares.diasa9', '=', null);
                })->orderBy('bdpaliativos.state')->get();


            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                        $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                        $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>'.
                        $button = '<button type="button" name="fallecido" id="' . $datas->id . '" class="addfallecido btn btn-float btn-sm btn-danger tooltipsC" title="Adicionar fallecido"  ><i class="fas fa-bible"></i></button>' .
                        $button = '<button type="button" name="asociarpro" id="' . $datas->id . '" class="asociarpro btn btn-float btn-sm btn-info tooltipsC" title="Asociar a profesional"  ><i class="fas fa-clinic-medical"></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
                
        }else if ($rol_id == 3 || $rol_id == 2) {
            
            
              $datas1 = DB::table('bdpaliativos')->join('consulta_auxiliars', 'bdpaliativos.document', '=', 'consulta_auxiliars.documento')->whereIn('bdpaliativos.state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO', 'PACIENTE NO ACEPTA CITA'])
                ->select(
                    'bdpaliativos.document',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar1) as diasa1'),
                    'consulta_auxiliars.auxiliar1 as auxiliar1',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar2) as diasa2'),
                    'consulta_auxiliars.auxiliar2 as auxiliar2',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar3) as diasa3'),
                    'consulta_auxiliars.auxiliar3 as auxiliar3',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar4) as diasa4'),
                    'consulta_auxiliars.auxiliar4 as auxiliar4',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar5) as diasa5'),
                    'consulta_auxiliars.auxiliar5 as auxiliar5',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar6) as diasa6'),
                    'consulta_auxiliars.auxiliar6 as auxiliar6',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar7) as diasa7'),
                    'consulta_auxiliars.auxiliar7 as auxiliar7',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar8) as diasa8'),
                    'consulta_auxiliars.auxiliar8 as auxiliar8',
                    DB::raw('DATEDIFF(now(), consulta_auxiliars.auxiliar9) as diasa9'),
                    'consulta_auxiliars.auxiliar9 as auxiliar9'
                );



                $datas = DB::table('bdpaliativos')
                ->JoinSub($datas1, 'auxiliares', function ($join) {
                $join->on('bdpaliativos.document', '=', 'auxiliares.document');
                })->where(function ($query) {
                    $query->where('auxiliares.diasa1', '>', 30)
                          ->orWhere('auxiliares.diasa1', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa2', '>', 30)
                          ->orWhere('auxiliares.diasa2', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa3', '>', 30)
                          ->orWhere('auxiliares.diasa3', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa4', '>', 30)
                          ->orWhere('auxiliares.diasa4', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa5', '>', 30)
                          ->orWhere('auxiliares.diasa5', '=', null);
                })
                ->where(function ($query) {
                    $query->where('auxiliares.diasa6', '>', 30)
                          ->orWhere('auxiliares.diasa6', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa7', '>', 30)
                          ->orWhere('auxiliares.diasa7', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa8', '>', 30)
                          ->orWhere('auxiliares.diasa8', '=', null);
                })->where(function ($query) {
                    $query->where('auxiliares.diasa9', '>', 30)
                          ->orWhere('auxiliares.diasa9', '=', null);
                })->where('bdpaliativos.profesional','Enfermeria')->orderBy('bdpaliativos.state')->get();


            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                        $button = '<button type="button" name="novedad" id="' . $datas->id . '" class="novedad btn btn-float btn-sm btn-success tooltipsC" title="Adicionar novedad"  ><i class="fas fa-notes-medical "></i></button>' .
                        $button = '<button type="button" name="estado" id="' . $datas->id . '" class="addestado btn btn-float btn-sm btn-warning tooltipsC" title="Adicionar estado"  ><i class="fas fa-user-check"></i></button><br>';
                        

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        
        
        }
        }


    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($idp)
    {
        $data = Usuario::with('roles1')->get();

        $pacientebd = BasePaliativos::with('obspaliativos')->where('id', $idp)->get();

        //where('id',$idp)->whit()->get();

        return response()->json([['pacientebd' => $pacientebd], ['usuario' => $data]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {

            $rules = array(
                'surname' => 'required',
                'fname' => 'required',
                'type_document' => 'required',
                'document' => 'required|unique:bdpaliativos',
                'state' => 'required',
                'type' => 'required',
                'user_id' => 'required',
                'estado_paci' => 'required'
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            BasePaliativos::create($request->all());

            return response()->json(['success' => 'ok']);
        }
    }

    public function actualizarpro(Request $request)
    {
        if ($request->ajax()) {

            $rules = array(
                'profesional' => 'required'
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }


            $profesional = BasePaliativos::findOrFail($request->id);
            $profesional->update($request->all());

            return response()->json(['success' => 'ok1']);
        }
    }
    
        public function actualizarpaciente(Request $request)
    {
        if ($request->ajax()) {

            $rules = array(
                'surname' => 'required',
                'fname' => 'required',
                'sex' => 'required',
                'date_in' => 'required',
                'estado_paci' => 'required'
                
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }


            $paciente = BasePaliativos::findOrFail($request->id);
            $paciente->update($request->all());

            return response()->json(['success' => 'ok2']);
        }
    }
    
    

    public function actualizarestado(Request $request)
    {
        if ($request->ajax()) {

            $rules = array(
                'state' => 'required',
                'type' => 'required',
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }

            $profesional = BasePaliativos::findOrFail($request->id);

            if ($profesional->state == 'FALLECIDO') {

                return response()->json(['success' => 'dead']);
            } else {

                $profesional = BasePaliativos::findOrFail($request->id);
                $profesional->update($request->all());

                return response()->json(['success' => 'ok1']);
            }
        }
    }

    public function actualizarfallecido(Request $request)
    {
        if ($request->ajax()) {

            $rules = array(
                'date_dead' => 'required'

            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }


            $profesional = BasePaliativos::findOrFail($request->id);

            if ($profesional->state == 'FALLECIDO') {

                return response()->json(['success' => 'dead']);
            } else {

                $profesional = BasePaliativos::findOrFail($request->id);
                $profesional->update($request->all());

                return response()->json(['success' => 'ok1']);
            }
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paliativos\BasePaliativos  $basePaliativos
     * @return \Illuminate\Http\Response
     */
    public function edit(BasePaliativos $basePaliativos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paliativos\BasePaliativos  $basePaliativos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BasePaliativos $basePaliativos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paliativos\BasePaliativos  $basePaliativos
     * @return \Illuminate\Http\Response
     */
    public function informes(Request $request)
    {

        if ($request->ajax()) {

            $fechaini = new Carbon($request->fechaini);
            $fechaini = $fechaini->toDateString();

            $fechafin = new Carbon($request->fechafin);
            $fechafin = $fechafin->toDateString();

            $usuario = $request->usuario;
            $valor_hora_add = 0;

            //Consulta de fallecidos
            $dead = BasePaliativos::where('state', 'FALLECIDO')->count();

            //Consulta de total de pacientes en paliativos
            $totalBdPaliativos = BasePaliativos::count();

            //consulta de ambulatorios
            $totalBdAmbulatorio = BasePaliativos::where([['type', 'AMBULATORIO']])->whereIn('state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO'])->count();

            //consulta de Domiciliarios
            $totalDomi = BasePaliativos::where([['type', 'DOMICILIARIO']])->whereIn('state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO'])->count();

            //Consulta de pacientes activos

            $totalBdActivos = BasePaliativos::whereIn('state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO'])->count();


            //Consulta de pacientes activos

            $totalBdSincontacto = BasePaliativos::whereIn('state', ['SIN CONTACTO'])->count();

            //Consulta de pacientes No quieren la consulta

            $totalBdNoaceptancitas = BasePaliativos::whereIn('state', ['PACIENTE NO ACEPTA CITA'])->count();

            //Consulta total de alarmas

            $totalAlarmas = ObsPaliativos::whereIn('type_obs', ['ALERTA'])->count();

            //Consulta total Agresadod

            $totalEgresados = BasePaliativos::whereIn('state', ['FALLECIDO', 'EGRESADO'])->count();
    
            //Consulta total Pacientes

            $totalPacientespro = BasePaliativos::whereIn('state', ['ATENDIO', 'ASIGNADO', 'ATENDIDO'])
            ->select(
                 DB::raw('SUM(CASE WHEN profesional = "clopez" THEN 1 ELSE 0 END) AS clopez'),
                 DB::raw('SUM(CASE WHEN profesional = "agutierrez" THEN 1 ELSE 0 END) AS agutierrez'),
                 DB::raw('SUM(CASE WHEN profesional = "blopez" THEN 1 ELSE 0 END) AS blopez'),
                 DB::raw('SUM(CASE WHEN profesional = "dbotero" THEN 1 ELSE 0 END) AS dbotero'),
                 DB::raw('SUM(CASE WHEN profesional = "lvalencia" THEN 1 ELSE 0 END) AS lvalencia'),
                 DB::raw('SUM(CASE WHEN profesional = "ncelis" THEN 1 ELSE 0 END) AS ncelis'),
                 DB::raw('SUM(CASE WHEN profesional is null THEN 1 ELSE 0 END) AS nuevos')
                 )->get();
        


            return response()->json([
                'sinc' => $totalBdSincontacto, 'result' => $dead, 'result1' => $totalBdPaliativos, 'result2' => $totalBdAmbulatorio,
                'result3' => $totalBdActivos, 'alarmas' => $totalAlarmas, 'domiciliarios' => $totalDomi, 'egresados' => $totalEgresados, 'noacepta' => $totalBdNoaceptancitas , 'tpacientesp' => $totalPacientespro
            ]);
        }
    }


    public function import(Request $request)
    {

        if ($request->ajax()) {
            $file = $request->file('file2');

            if ($file == null) {

                return response()->json(['mensaje' => 'vacio']); //return redirect('admin/archivo')->with('mensaje', 'No seleccionaste ningun archivo');


            } else {

                $this->importaExcel($request);




                return response()->json(['mensaje' => 'ok']); //return redirect('admin/archivo')->with('mensaje', 'Archivo cargado exitosamente');


            }
        }
    }


    public function importaExcel(Request $request)
    {

        // Guardo la colección en $file

        $file = $request->file('file2');

        $name = time() . $file->getClientOriginalName();


        $destinationPath = public_path('importbd/');

        $file->move($destinationPath, $name);

        $path = $destinationPath . $name;

        // $import = new EstadosImport();

        Excel::import(new Pacientesimport, $path);
    }
    
      public function selectpro()
    {
        if(request()->ajax())
        {
          $selectpro=BasePaliativos::select('profesional')->groupBy('profesional')->get();
            return response()->json($selectpro);
        }
    }
    
      public function selectzona()
    {
        if(request()->ajax())
        {
         $selectzona=BasePaliativos::select('future1')->groupBy('future1')->get();
            return response()->json($selectzona);
        }
    }
    
      public function selectpac()
    {
        if(request()->ajax())
        {
          $selectestado=Estados::select('estado_pac')->groupBy('estado_pac')->get();
            return response()->json($selectestado);
        }
    }
    
    
     public function importambito(Request $request)
    {

        if ($request->ajax()) {
            $file = $request->file('file3');

            if ($file == null) {

                return response()->json(['mensaje' => 'vacio']); //return redirect('admin/archivo')->with('mensaje', 'No seleccionaste ningun archivo');


            } else {

                $this->importaExcela($request);




                return response()->json(['mensaje' => 'ok', $rowCount] ); //return redirect('admin/archivo')->with('mensaje', 'Archivo cargado exitosamente');


            }
        }
    }


    public function importaExcela(Request $request)
    {

        // Guardo la colección en $file

        $file = $request->file('file3');

        $name = time() . $file->getClientOriginalName();


        $destinationPath = public_path('importbd/');

        $file->move($destinationPath, $name);

        $path = $destinationPath . $name;

        // $import = new EstadosImport();

        Excel::import(new AmbitoImport, $path);
        
     
        
         // Puedes retornar información adicional, como la cantidad de registros procesados
        $rowCount = (new AmbitoImport)->getRowCount();
        return $rowCount;
    }
}

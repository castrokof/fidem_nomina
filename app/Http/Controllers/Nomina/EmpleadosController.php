<?php

namespace App\Http\Controllers\Nomina;


use App\Events\UpdateNovedadEmp;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidacionEmpleado;
use App\Models\nomina\Empleados;
use App\Models\Seguridad\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpleadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usuario_id = $request->session()->get('usuario_id');



    if($request->ajax()){

        if($request->session()->get('rol_id') == 1 || $request->session()->get('rol_id') == 4){

            $datas = Empleados::orderBy('id')->get();

           return  DataTables()->of($datas)
            ->addColumn('action', function($datas){
            $button = '<button type="button" name="edit" id="'.$datas->id.'"
            class = "editemployed btn-float  bg-gradient-primary btn-sm tooltipsC"  title="Editar usuario"><i class="fas fa-user-edit"></i></button>';
            $button .='&nbsp;<button type="button" name="addnovedad" id="'.$datas->id.'" usuario1="'.$datas->usuario.'"
            class = "addnovedad btn-float  bg-gradient-warning btn-sm tooltipsC" title="Adicionar novedad"><i class="fas fa-plus-square"></i></button>';
             $button .='&nbsp;<button type="button" name="add_contrato" id="'.$datas->id.'" usuario1="'.$datas->usuario.'"
            class = "addcontrato btn-float  bg-gradient-danger btn-sm tooltipsC" title="Adicionar contrato"><i class="fas fa-file-alt"></i></button>';

          return $button;

            })
            ->rawColumns(['action'])
            ->make(true);

         }else  if($request->session()->get('rol_id') == 2){

            $datas = Empleados::orderBy('id')
            ->where('user_id',  $usuario_id )
            ->get();

        return  DataTables()->of($datas)
        ->addColumn('action', function($datas){
        $button = '<button type="button" name="edit" id="'.$datas->id.'"
        class = "editemployed btn-float  bg-gradient-primary btn-sm tooltipsC"  title="Editar usuario"><i class="fas fa-user-edit"></i></button>';
        $button .='&nbsp;<button type="button" name="editpass" id="'.$datas->id.'"
        class = "epassword btn-float  bg-gradient-warning btn-sm tooltipsC" title="Editar password"><i class="fas fa-plus-square"></i></button>';
        return $button;

          })
          ->rawColumns(['action'])
          ->make(true);

        }


    }

        return view('nomina.empleados.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function select(Request $request)
        {
            
            
            $array=[];


        if($request->has('q'))
        {
            $term = $request->get('q');


            array_push($array, Empleados::select('pnombre','papellido','documento')->where(
             'pnombre', 'LIKE', '%' . $term . '%')
             ->orWhere(
             'papellido', 'LIKE', '%' . $term . '%')
             ->orWhere(
             'documento', 'LIKE', '%' . $term . '%')
             ->groupby('pnombre','papellido','documento')
             ->get());

            return response()->json(['array'=>$array]);
        }else {



                array_push($array, Empleados::select('pnombre','papellido','documento')
                ->groupby('pnombre','papellido','documento')
                ->get());


                return response()->json(['array'=>$array]);



        }   
            
            /*if(request()->ajax())
            {
              $quincenas=nominaliquid::select('quincena')->groupby('quincena')->get();
                return response()->json($quincenas);
            }*/
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidacionEmpleado $request, Empleados $empleados)
    {

            Empleados::create($request->all());

           // event(new UpdateNovedadEmp($empleados));

            return response()->json(['success' => 'ok']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\nomina\Empleados  $empleados
     * @return \Illuminate\Http\Response
     */
    public function show(Empleados $empleados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\nomina\Empleados  $empleados
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $empleados)
    {
        if($request->ajax()){

           $empleado = Empleados::findOrFail($empleados);
        }

        return response()->json(['empleado'=>$empleado]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\nomina\Empleados  $empleados
     * @return \Illuminate\Http\Response
     */
    public function update(ValidacionEmpleado $request, $empleados)
    {

        if($request->ajax()){

            Empleados::findOrFail($empleados)
            ->update($request->all());
         }

         return response()->json(['success' => 'ok1']);




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\nomina\Empleados  $empleados
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empleados $empleados)
    {
        //
    }
}

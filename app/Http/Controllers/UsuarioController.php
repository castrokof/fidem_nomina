<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidacionUsuario;
use App\Models\Admin\Rol;
use App\Models\Nomina\Position;
use App\Models\Seguridad\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usuario_id = $request->session()->get('usuario_id');
        $Rols1 = Rol::orderBy('id')->pluck('nombre', 'id')->toArray();


    if($request->ajax()){

        if($request->session()->get('rol_id') == 1){

            $datas = Usuario::with('roles1')->get();

           return  DataTables()->of($datas)
            ->addColumn('action', function($datas){
            $button = '<button type="button" name="edit" id="'.$datas->id.'"
            class = "edit btn-float  bg-gradient-primary btn-sm tooltipsC"  title="Editar usuario"><i class="fas fa-user-edit"></i></button>';
            $button .='&nbsp;<button type="button" name="editpass" id="'.$datas->id.'" usuario1="'.$datas->usuario.'"
            class = "epassword btn-float  bg-gradient-warning btn-sm tooltipsC" title="Editar password"><i class="fas fa-key"></i></button>';

          return $button;

            })
            ->rawColumns(['action'])
            ->make(true);

         }else  if($request->session()->get('rol_id') == 2){

            $datas = Usuario::with('roles1')
            ->where('id',  $usuario_id )
            ->get();

        return  DataTables()->of($datas)
        ->addColumn('action', function($datas){
        $button = '<button type="button" name="edit" id="'.$datas->id.'"
        class = "edit btn-float  bg-gradient-primary btn-sm tooltipsC"  title="Editar usuario"><i class="fas fa-user-edit"></i></button>';
        $button .='&nbsp;<button type="button" name="editpass" id="'.$datas->id.'"
        class = "epassword btn-float  bg-gradient-warning btn-sm tooltipsC" title="Editar password"><i class="fas fa-key"></i></button>';
        return $button;

          })
          ->rawColumns(['action'])
          ->make(true);

        }


    }

   return view('admin.usuario.index', compact('Rols1'));
 }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function select()
    {
        if(request()->ajax())
        {
          $usuarios=Usuario::orderBy('id')->get();
            return response()->json($usuarios);
        }
    }


     public function crear()
    {
        return view('admin.usuario.crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(ValidacionUsuario  $request)
    {


        $usuario = Usuario::create($request->all());
        $usuario->roles1()->attach($request->rol_id);


        return response()->json(['success' => 'ok']);




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar(Request $request, $id)
    {

        if($request->session()->get('rol_id') == 1)
        {
        $Rols1 = Rol::orderBy('id')->pluck('nombre', 'id')->toArray();
        }else{
        $Rols1 = Rol::orderBy('id')->where([['id', '!=', 1],['id', '!=', 2]])->pluck('nombre', 'id')->toArray();
        }

        if(request()->ajax()){

            $data = DB::table('usuario')
            ->Join('usuario_rol', 'usuario.id', '=', 'usuario_rol.usuario_id')
            ->Join('rol', 'usuario_rol.rol_id', '=', 'rol.id')
            ->select('usuario.id as id', 'usuario.pnombre as pnombre', 'usuario.snombre as snombre', 'usuario.papellido as papellido','usuario.sapellido as sapellido', 'rol.nombre as nombre',
            'usuario.tipo_documento as tipo_documento', 'usuario.documento as documento', 'usuario.usuario as usuario', 'usuario.celular as celular',
            'usuario.email as email', 'usuario.ips as ips', 'usuario.activo as activo', 'usuario.password as password','usuario.remenber_token as remenber_token', 'rol.id as rol_id', 'usuario.created_at as created_at')
            ->orderBy('usuario.id')
            ->where('usuario.id', $id)
            ->first();



            return response()->json(['result'=>$data]);


        }

        return view('admin.usuario.editar', compact('data','Rols1'));
    }

    public function editarpassword($id)
    {   $data = Usuario::with('roles1')->findOrFail($id);
        return view('admin.usuario.editarpassword', compact('data'));
    }

    public function editarpassword1($id)
    {   $data = Usuario::with('roles1')->findOrFail($id);
        return view('admin.usuario.editarpassword1', compact('data'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar(ValidacionUsuario $request, $id)
    {

        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());
        $usuario->roles1()->sync($request->rol_id);

        return response()->json(['success' => 'ok1']);
    }

    public function actualizarpassword(Request $request, $id)
    {
       Usuario::findOrFail($id)->update($request->all());

        return redirect('usuario')->with('mensaje', 'Password actualizado con exito!!');
    }
    public function actualizarpassword1(Request $request, $id)
    {
        if ($request->ajax()) {

        if($request->password == $request->remenber_token){
            Usuario::findOrFail($id)->update($request->all());
                return response()->json(['respuesta' => 'ok']);
            }else{

                return response()->json(['respuesta' => 'ng']);
            }
        }
            return redirect('admin/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function eliminar($id)
    {
        //
    }


    public function consultarusuario($id)
    {
        if(request()->ajax())
        {
          $result= Usuario::findOrFail($id);
            return response()->json($result);
        }
    }




}

<?php

namespace App\Http\Controllers\Nomina;
use App\Http\Controllers\Controller;
use App\Models\Nomina\Novedades;
use Illuminate\Http\Request;

class NovedadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(request()->ajax())
        {
          $data=Novedades::findOrFail($request->id);
          return  DataTables()->of($data)
        //   ->addColumn('action', function($datas){
        //   $button = '<button type="button" name="edit" id="'.$datas->id.'"
        //   class = "edit btn-float  bg-gradient-primary btn-sm tooltipsC"  title="Editar usuario"><i class="fas fa-user-edit"></i></button>';
        //   $button .='&nbsp;<button type="button" name="editpass" id="'.$datas->id.'" usuario1="'.$datas->usuario.'"
        //   class = "epassword btn-float  bg-gradient-warning btn-sm tooltipsC" title="Editar password"><i class="fas fa-key"></i></button>';

        // return $button;

        //   })
        //   ->rawColumns(['action'])
          ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

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

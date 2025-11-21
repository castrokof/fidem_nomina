<?php

namespace App\Http\Controllers\Paliativos;

use App\Http\Controllers\Controller;
use App\Imports\UltimaAtencionAuxulilarImport;
use App\Models\ConsultaAuxiliar;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ConsultaAuxiliarController extends Controller
{
    public function import(Request $request)
    {

   if($request->ajax()){

    $file = $request->file('file3');

        if($file == null){

         return response()->json(['mensaje' => 'vacio']);//return redirect('admin/archivo')->with('mensaje', 'No seleccionaste ningun archivo');


        }else{

        $this->importaExcel($request);




               return response()->json(['mensaje' => 'ok']); //return redirect('admin/archivo')->with('mensaje', 'Archivo cargado exitosamente');


        }

    }

   }


    public function importaExcel(Request $request)
    {

         // Guardo la colección en $file

       $file = $request->file('file3');

       $name=time().$file->getClientOriginalName();


        $destinationPath = public_path('importbd/');

        $file->move($destinationPath, $name);

        $path=$destinationPath.$name;

       // $import = new EstadosImport();

    Excel::import(new UltimaAtencionAuxulilarImport, $path);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

//     /**
//      * Display the specified resource.
//      *
//      * @param  \App\Models\ConsultaAuxiliar  $consultaAuxiliar
//      * @return \Illuminate\Http\Response
//      */
//     public function show(ConsultaAuxiliar $consultaAuxiliar)
//     {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  \App\Models\ConsultaAuxiliar  $consultaAuxiliar
//      * @return \Illuminate\Http\Response
//      */
//     public function edit(ConsultaAuxiliar $consultaAuxiliar)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \App\Models\ConsultaAuxiliar  $consultaAuxiliar
//      * @return \Illuminate\Http\Response
//      */
//     public function update(Request $request, ConsultaAuxiliar $consultaAuxiliar)
//     {
//         //
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  \App\Models\ConsultaAuxiliar  $consultaAuxiliar
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy(ConsultaAuxiliar $consultaAuxiliar)
//     {
//         //
//     }
}

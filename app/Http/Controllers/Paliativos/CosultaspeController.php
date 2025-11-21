<?php
namespace App\Http\Controllers\Paliativos;

use App\Http\Controllers\Controller;
use App\Imports\UltimaAtencionPEImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CosultaspeController extends Controller
{


    public function import(Request $request)
    {

   if($request->ajax()){

    $file = $request->file('file1');

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

       $file = $request->file('file1');

       $name=time().$file->getClientOriginalName();


        $destinationPath = public_path('importbd/');

        $file->move($destinationPath, $name);

        $path=$destinationPath.$name;

       // $import = new EstadosImport();

    Excel::import(new UltimaAtencionPEImport, $path);

    }
}

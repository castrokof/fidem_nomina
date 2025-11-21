<?php
namespace App\Http\Controllers\Paliativos;

use App\Http\Controllers\Controller;
use App\Imports\EstadosImport;
use App\Models\Paliativos\Estados;
use App\Models\Paliativos\BasePaliativos;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class EstadosController extends Controller
{

  public  $totalcargados = 0;

    public function index()
    {
        return view('admin.import.index');
    }


    public function import(Request $request)
    {

   if($request->ajax()){
      $file = $request->file('file');

        if($file == null){

         return response()->json(['mensaje' => 'vacio']);//return redirect('admin/archivo')->with('mensaje', 'No seleccionaste ningun archivo');


        }else{

        $this->importaExcel($request);
        $this->update_state();




               return response()->json(['mensaje' => 'ok']); //return redirect('admin/archivo')->with('mensaje', 'Archivo cargado exitosamente');


        }

    }

   }


    public function importaExcel(Request $request, $totalcargados = '')
    {

         // Guardo la colección en $file

       $file = $request->file('file');

       $name=time().$file->getClientOriginalName();


        $destinationPath = public_path('importbd/');

        $file->move($destinationPath, $name);

        $path=$destinationPath.$name;

       // $import = new EstadosImport();

    Excel::import(new EstadosImport, $path);


    }
    
    
    function update_state()
    {
    
       $estadomax =  DB::table('estados')
            ->select(DB::raw('MAX(id_estado) as last_id_estado'))
            ->groupBy('documento');

        $estadomaxc = DB::table('estados')
            ->rightJoinSub($estadomax, 'last_ids', function ($join) {
                $join->on('estados.id_estado', '=', 'last_ids.last_id_estado');
            })->get();
            
            
        foreach($estadomaxc as $rows){
            
            switch ($rows->estado_pac) {
                        case 1:
                            $pro = 'Enfermeria';
                            break;
                        case 2:
                             $pro = 'Medico Experto';
                            break;
                        case 3:
                             $pro = 'Medico Especialista';
                            break;
                    }
            
            
            DB::table('bdpaliativos')->where('document', $rows->documento)
            ->update([
                'estado_paci' => $rows->estado_pac,
                'profesional' => $pro
                     ]);
            
            
        }    



    }
    
    
    
    
    
}

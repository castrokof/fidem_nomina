<?php

namespace App\Http\Controllers\Paliativos;

use App\Http\Controllers\Controller;
use App\Models\Paliativos\BasePaliativos;
use App\Models\Paliativos\ObsPaliativos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class ObsPaliativosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {



             $datas = ObsPaliativos::with('pacid')
             ->select('obspaliativos.*')
            ->whereIn('obspaliativos.type_obs',['ALERTA'])->get();

            return  DataTables()->of($datas)
                ->addColumn('action', function ($datas) {
                    $button = '<button type="button" name="deletealertt" id="' . $datas->id . '" class="deletealertt btn btn-float btn-sm btn-warning tooltipsC" title="elminar alerta"  ><i class="fas fa-notes-medical "></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('Paliativos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function index_hospi()
    { 
     return view('paliativos.index_hospi');
    }
    public function index_hospi1(Request $request)
    {
        
       if(empty($request->fechaini) || empty($request->fechafin)){
            
            $fechaini = Carbon::now();
            $fechaini = $fechaini->Format('Y-m-d')." 00:00:01";
            
            $fechafin = Carbon::now();
            $fechafin = $fechafin->Format('Y-m-d')." 23:59:59";
            
          }else{
            $fechaini = $request->fechaini." 00:00:01";
            $fechafin = $request->fechafin." 23:59:59";
                }
       
        
        
        if($request->ajax()){
            
            
           
            
             $datas = DB::table('bdpaliativos')
                      ->leftJoin('obspaliativos', 'bdpaliativos.id' , 'obspaliativos.pac_id')
                      ->leftJoin('cosultaspes', 'bdpaliativos.document' , 'cosultaspes.documento')
                      ->select('bdpaliativos.*',  'obspaliativos.future1', 'obspaliativos.type_obs', 'obspaliativos.subtype_obs', 'cosultaspes.*')
                      ->selectRaw('TIMESTAMPDIFF(YEAR, bdpaliativos.date_birth, now()) as edad')
                      ->selectRaw('obspaliativos.created_at as creacion')
                      ->selectRaw('obspaliativos.observacion as observacionhospi')
                      ->whereIn('obspaliativos.type_obs', ['HOSPITALIZADO'])
                      ->whereBetween('obspaliativos.created_at', [$fechaini, $fechafin])
                      ->get();
             
             
             
            return  DataTables()->of($datas)
                    ->make(true);
            
        }
        
        return view('paliativos.index_hospi');
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

                'observacion' => 'required',
                'type_obs' => 'required',
                'pac_id' => 'required',
                'user_id' => 'required'
            );

            $error = Validator::make($request->all(), $rules);

            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }


            ObsPaliativos::create($request->all());

         
            return response()->json(['success' => 'ok']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paliativos\ObsPaliativos  $obsPaliativos
     * @return \Illuminate\Http\Response
     */
    public function show(ObsPaliativos $obsPaliativos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paliativos\ObsPaliativos  $obsPaliativos
     * @return \Illuminate\Http\Response
     */
    public function edit(ObsPaliativos $obsPaliativos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paliativos\ObsPaliativos  $obsPaliativos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ObsPaliativos $obsPaliativos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paliativos\ObsPaliativos  $obsPaliativos
     * @return \Illuminate\Http\Response
     */
    public function destroy(ObsPaliativos $obsPaliativos)
    {
        //
    }

    public function deletealert($id)
    {
        ObsPaliativos::destroy($id);

        return response()->json(['repuesta'=>'delete']);
    }
}

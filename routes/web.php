<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Paliativos\FidemContigoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}




/* RUTAS IMAGENES TEXTO */

//Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
//Route::get('/', 'Admin\InicioController@index')->name('inicio');

Route::get('/paciente/{documento}', [PacienteController::class, 'mostrarFormulario'])->name('mostrar.formulario');


//Ruta para guardar pacientes
Route::post('/guardar-paciente', [FidemContigoController::class, 'store'])->name('guardar.paciente');


//Ruta para ver observaciones
// Route::get('/ver-observaciones/{id}', [FidemContigoController::class, 'verObservaciones'])->name('ver.observaciones');


Route::get('/buscar-paciente/{documento}', [FidemContigoController::class, 'buscarPaciente']);






//Ruta para ver el formulario de observaciones
Route::get('/fidemcontigo', [FidemContigoController::class, 'observaciones'])->name('fidemcontigo.index');


Route::get('/', 'Seguridad\LoginController@index')->name('inicio');
Route::get('seguridad/login', 'Seguridad\LoginController@index')->name('login');
Route::post('seguridad/login', 'Seguridad\LoginController@login')->name('login_post');
Route::get('seguridad/logout', 'Seguridad\LoginController@logout')->name('logout');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'superadmin']], function () {


    /* RUTAS DEL MENU */
    Route::get('menu', 'MenuController@index')->name('menu');
    Route::get('menu/crear', 'MenuController@crear')->name('crear_menu');
    Route::get('menu/{id}/editar', 'MenuController@editar')->name('editar_menu');
    Route::put('menu/{id}', 'MenuController@actualizar')->name('actualizar_menu');
    Route::post('menu', 'MenuController@guardar')->name('guardar_menu');
    Route::post('menu/guardar-orden', 'MenuController@guardarOrden')->name('guardar_orden');
    Route::get('rol/{id}/elimniar', 'MenuController@eliminar')->name('eliminar_menu');

    /* RUTAS DEL ROL */
    Route::get('rol', 'RolController@index')->name('rol');
    Route::get('rol/crear', 'RolController@crear')->name('crear_rol');
    Route::post('rol', 'RolController@guardar')->name('guardar_rol');
    Route::get('rol/{id}/editar', 'RolController@editar')->name('editar_rol');
    Route::put('rol/{id}', 'RolController@actualizar')->name('actualizar_rol');
    Route::delete('rol/{id}', 'RolController@eliminar')->name('eliminar_rol');

    /* RUTAS DEL MENUROL */
    Route::get('menu-rol', 'MenuRolController@index')->name('menu_rol');
    Route::post('menu-rol', 'MenuRolController@guardar')->name('guardar_menu_rol');

    /* RUTAS DE LA EMPRESA */
    Route::get('empresa', 'EmpresaController@index')->name('empresa');
    Route::get('empresa/crear', 'EmpresaController@crear')->name('crear_empresa');
    Route::post('empresa', 'EmpresaController@guardar')->name('guardar_empresa');
    Route::get('empresa/{id}/editar', 'EmpresaController@editar')->name('editar_empresa');
    Route::put('empresa/{id}', 'EmpresaController@actualizar')->name('actualizar_empresa');

    /* RUTAS DEL PERMISO */
    Route::get('permiso', 'PermisoController@index')->name('permiso');
    Route::get('permiso/crear', 'PermisoController@crear')->name('crear_permiso');
    Route::post('permiso', 'PermisoController@guardar')->name('guardar_permiso');
    Route::get('permiso/{id}/editar', 'PermisoController@editar')->name('editar_permiso');
    Route::put('permiso/{id}', 'PermisoController@actualizar')->name('actualizar_permiso');
    Route::delete('permiso/{id}', 'PermisoController@eliminar')->name('eliminar_permiso');

    /* RUTAS DEL PERMISOROL */
    Route::get('permiso-rol', 'PermisoRolController@index')->name('permiso_rol');
    Route::post('permiso-rol', 'PermisoRolController@guardar')->name('guardar_permiso_rol');

    /* RUTAS DE MEDICAMENTOS CONTROLADOS */
    Route::get('medicamento-controlado', 'MedicamentoControladoController@index')->name('medicamento_controlado');
    Route::get('medicamento-controlado/crear', 'MedicamentoControladoController@crear')->name('crear_medicamento_controlado');
    Route::post('medicamento-controlado', 'MedicamentoControladoController@guardar')->name('guardar_medicamento_controlado');
    Route::get('medicamento-controlado/{id}/editar', 'MedicamentoControladoController@editar')->name('editar_medicamento_controlado');
    Route::put('medicamento-controlado/{id}', 'MedicamentoControladoController@actualizar')->name('actualizar_medicamento_controlado');
    Route::get('medicamento-controlado/{id}/eliminar', 'MedicamentoControladoController@eliminar')->name('eliminar_medicamento_controlado');

    /* RUTAS DE MOVIMIENTOS DE MEDICAMENTOS CONTROLADOS */
    Route::get('medicamento-controlado-movimiento', 'MedicamentoControladoMovimientoController@index')->name('medicamento_controlado_movimiento');
    Route::get('medicamento-controlado-movimiento/crear-entrada', 'MedicamentoControladoMovimientoController@crearEntrada')->name('crear_entrada_medicamento_controlado');
    Route::get('medicamento-controlado-movimiento/crear-salida', 'MedicamentoControladoMovimientoController@crearSalida')->name('crear_salida_medicamento_controlado');
    Route::post('medicamento-controlado-movimiento/guardar', 'MedicamentoControladoMovimientoController@guardar')->name('guardar_medicamento_controlado_movimiento');
    Route::get('medicamento-controlado-movimiento/saldo/{medicamento_id}', 'MedicamentoControladoMovimientoController@obtenerSaldo')->name('obtener_saldo_medicamento');
    Route::get('medicamento-controlado-movimiento/estadisticas', 'MedicamentoControladoMovimientoController@obtenerEstadisticas')->name('obtener_estadisticas_movimientos');
    Route::get('medicamento-controlado-movimiento/{id}', 'MedicamentoControladoMovimientoController@mostrar')->name('mostrar_medicamento_controlado_movimiento');
});


Route::group(['middleware' => ['auth']], function () {

    Route::get('/tablero', 'AdminController@index')->name('tablero');

    Route::get('informes', 'AdminController@informes')->name('informes')->middleware('superConsultor');

    /* RUTAS DEL USUARIO */
    Route::get('usuario', 'UsuarioController@index')->name('usuario')->middleware('superEditor');
    Route::get('usuario/crear', 'UsuarioController@crear')->name('crear_usuario')->middleware('superEditor');
    Route::post('usuario', 'UsuarioController@guardar')->name('guardar_usuario')->middleware('superEditor');
    Route::get('usuario/{id}/editar', 'UsuarioController@editar')->name('editar_usuario')->middleware('superEditor');
    Route::get('usuario/{id}/password', 'UsuarioController@editarpassword')->name('editar_password')->middleware('superEditor');
    Route::put('usuario/{id}', 'UsuarioController@actualizar')->name('actualizar_usuario')->middleware('superEditor');
    Route::put('password/{id}', 'UsuarioController@actualizarpassword')->name('actualizar_password')->middleware('superEditor');
    Route::put('password1/{id}', 'UsuarioController@actualizarpassword1')->name('actualizar_password1');
    Route::get('editar_novedades/{id}', 'UsuarioController@consultarusuario')->name('editar_novedades')->middleware('superEditor');

    /* RUTAS DE EMPLEADO */

    Route::get('empleado', 'Nomina\EmpleadosController@index')->name('empleado')->middleware('superAnalista');
    Route::post('empleado', 'Nomina\EmpleadosController@store')->name('guardar_empleado')->middleware('superAnalista');
    Route::get('empleado/{id}/editar', 'Nomina\EmpleadosController@edit')->name('editar_empleado')->middleware('superAnalista');
    Route::put('empleado/{id}', 'Nomina\EmpleadosController@update')->name('actualizar_empleado')->middleware('superAnalista');
    Route::get('select_emp', 'Nomina\EmpleadosController@select')->name('select_emp');

   /* RUTAS DE EMPLEADO */

   Route::get('novedades', 'Nomina\NovedadesController@index')->name('novedades')->middleware('superAnalista');
   Route::post('novedades', 'Nomina\NovedadesController@store')->name('guardar_novedades')->middleware('superAnalista');
   Route::get('novedades/{id}/editar', 'Nomina\NovedadesController@edit')->name('editar_novedadesn')->middleware('superAnalista');
   Route::put('novedades{id}', 'Nomina\NovedadesController@update')->name('actualizar_novedades')->middleware('superAnalista');

    /* RUTAS DE HORAS X USUARIO */

    Route::get('nominaliquid', 'Nomina\NominaliquidController@index')->name('hours')->middleware('superAnalista');
    Route::post('nominaliquid', 'Nomina\NominaliquidController@store')->name('guardar_turno')->middleware('superAnalista');
    Route::get('nominaliquid/{id}/editar', 'Nomina\NominaliquidController@edit')->name('editar_turno')->middleware('superAnalista');
    Route::put('nominaliquid/{id}', 'Nomina\NominaliquidController@update')->name('actualizar_turno')->middleware('superAnalista');
    Route::post('liquidar', 'Nomina\NominaliquidController@supervisar')->name('liquidar');
    
    /* RUTAS DE HORAS X USUARIO */

    Route::get('hoursxuser', 'Nomina\HoursxuserController@index')->name('hoursxuser1')->middleware('superConsultor');
    Route::post('hoursxuser', 'Nomina\HoursxuserController@store')->name('guardar_turno1')->middleware('superConsultor');
    Route::get('hoursxuser/{id}/editar', 'Nomina\HoursxuserController@edit')->name('editar_turno1')->middleware('superConsultor');
    Route::put('hoursxuser/{id}', 'Nomina\HoursxuserController@update')->name('actualizar_turno1')->middleware('superConsultor');
    Route::post('liquidarh', 'Nomina\HoursxuserController@supervisar')->name('liquidarh')->middleware('superEditor');

    /* RUTAS DE NOMINA FIJA */

    Route::get('nominaf', 'Nomina\NominaliquidController@index_nominaf')->name('nominaf')->middleware('superAnalista');
    Route::post('nominaf_guardar', 'Nomina\NominaliquidController@store_nominaf')->name('guardar_nomina')->middleware('superAnalista');
    Route::get('informesnominafijos', 'Nomina\NominaliquidController@informesf')->name('informesnominaf')->middleware('superAnalista');
    Route::post('informesnominafijos1', 'Nomina\NominaliquidController@informesf1')->name('informesnominaf1')->middleware('superAnalista');
    // Route::get('nominaliquid/{id}/editar', 'Nomina\HoursxuserController@edit')->name('editar_turno')->middleware('superEditor');
    // Route::put('hoursxuser/{id}', 'Nomina\HoursxuserController@update')->name('actualizar_turno')->middleware('superEditor');
    // Route::post('liquidar', 'Nomina\HoursxuserController@supervisar')->name('liquidar');
    
    
    /* RUTAS DE NOMINA PS */

    //Route::get('nominaf1', 'Nomina\HoursxuserController@index_nominaf1')->name('nominaf1')->middleware('superEditor');
    //Route::post('nominaf_guardar', 'Nomina\HoursxuserController@store_nominaf')->name('guardar_nomina1')->middleware('superEditor');


    //RUTA PARA CONSULTA DE INFORMES
    Route::get('informesh', 'Nomina\NominaliquidController@informes')->name('hoursinfo')->middleware('superAnalista');
    Route::get('informeshc', 'Nomina\NominaliquidController@informes1')->name('hoursinfoc')->middleware('superAnalista');
    Route::get('select_user', 'UsuarioController@select')->name('select_user');



    //RUTA PARA CONSULTA DE INFORMES DE LIQUIDACION
    Route::get('informe-liquid', 'Nomina\LiquidationxuserController@informes')->name('liquidinfo')->middleware('superAnalista');
    Route::get('informe-liquidc', 'Nomina\LiquidationxuserController@informes1')->name('liquidinfoc')->middleware('superAnalista');
    Route::get('select_quincena', 'Nomina\NominaliquidController@select')->name('select_quincena');

    //RUTA PROCEDIMIENTOS

    Route::get('reporte_psicologia', 'Psicologica\LineaPsicologicaController@index')->name('reportepsico')->middleware('superPsicologica');
    Route::post('reporte_psicologia1', 'Psicologica\LineaPsicologicaController@index1')->name('reportepsico1')->middleware('superPsicologica');
    Route::post('guardar_evolucion', 'Psicologica\LineaPsicologicaController@store')->name('guardar_evolucion')->middleware('superPsicologica');
    Route::get('informe-psico', 'Psicologica\LineaPsicologicaController@informePsico')->name('informepsico')->middleware('superPsicologica');
    Route::get('consultar_procedimiento', 'Psicologica\LineaPsicologicaController@indexProcedimiento')->name('consultaprocedimiento')->middleware('superPsicologica');
    Route::post('consultar_procedimiento_table', 'Psicologica\LineaPsicologicaController@indexProcedimientotable')->name('consultaprocedimientotable')->middleware('superPsicologica');



    Route::get('consultar_evolucion', 'Psicologica\LineaPsicologicaController@indexAnalista')->name('analistapsico')->middleware('superPsicologica');
    Route::get('consultar_evoluciona', 'Psicologica\LineaPsicologicaController@indexAnalistaa')->name('analistapsicoa')->middleware('superPsicologica');
    Route::get('consultar_evolucions', 'Psicologica\LineaPsicologicaController@indexAnalistas')->name('analistapsicos')->middleware('superPsicologica');
    
    Route::post('consultar_evolucion1', 'Psicologica\LineaPsicologicaController@indexAnalista1')->name('analistapsico1')->middleware('superPsicologica');
    Route::post('consultar_evoluciona1', 'Psicologica\LineaPsicologicaController@indexAnalistaa1')->name('analistapsicoa1')->middleware('superPsicologica');
    Route::post('consultar_evolucions1', 'Psicologica\LineaPsicologicaController@indexAnalistas1')->name('analistapsicos1')->middleware('superPsicologica');
    
    Route::get('evolucion/{id}', 'Psicologica\LineaPsicologicaController@detalleEvolucion')->name('detalleEvolucion')->middleware('superPsicologica');
    Route::get('addseguimiento/{id}', 'Psicologica\LineaPsicologicaController@addseguimiento')->name('addseguimiento')->middleware('superAnalista');

    Route::post('guardar_observacion', 'Psicologica\ObservacionesPsicologiaController@store')->name('guardar_observacion')->middleware('superPsicologica');

    Route::post('agendado', 'Psicologica\LineaPsicologicaController@agendadoEvolucion')->name('agendadoEvolucion')->middleware('superAnalista');
    
     Route::get('consultardocumento', 'Psicologica\LineaPsicologicaController@consultarDocumento')->name('consultardocumento')->middleware('superPsicologica');
     
     Route::put('anular_evolucion/{id}', 'Psicologica\LineaPsicologicaController@anularEvolucion')->name('anular_evolucion')->name('analistapsico')->middleware('superPsicologica');


    //RUTA LINEA AVA

    Route::get('ava-index', 'Psicologica\LineaPsicologicaController@indexava')->name('indexava')->middleware('superPsicologica');


    //RUTA PARA CONSULTA DE PALIATIVOS

    Route::get('paliativos-index', 'Paliativos\BasePaliativosController@index')->name('indexpaliativos')->middleware('superEditor')->middleware('superEditor');
    Route::post('paliativos-index1', 'Paliativos\BasePaliativosController@index1')->name('indexpaliativos1')->middleware('superEditor')->middleware('superEditor');
    Route::post('paliativos-indexsin', 'Paliativos\BasePaliativosController@indexsin')->name('indexpaliativossin')->middleware('superEditor')->middleware('superEditor');
    Route::post('paliativos-indexdomi', 'Paliativos\BasePaliativosController@indexdomi')->name('indexpaliativosdomi')->middleware('superEditor')->middleware('superEditor');
    Route::post('paliativos-indexupe', 'Paliativos\BasePaliativosController@indexupe')->name('indexpaliativosupe')->middleware('superEditor')->middleware('superEditor');
    Route::post('paliativos-indexupef', 'Paliativos\BasePaliativosController@indexupef')->name('indexpaliativosupef')->middleware('superEditor')->middleware('superEditor');
    Route::post('paliativos-indexue', 'Paliativos\BasePaliativosController@indexue')->name('indexpaliativosue')->middleware('superEditor')->middleware('superEditor');
    Route::post('paliativos-indexua', 'Paliativos\BasePaliativosController@indexua')->name('indexpaliativosua')->middleware('superEditor')->middleware('superEditor');
    Route::post('paliativos-indexa', 'Paliativos\BasePaliativosController@indexa')->name('indexpaliativosa')->middleware('superEditor')->middleware('superEditor');
    Route::post('crear-basepaliativos', 'Paliativos\BasePaliativosController@store')->name('crear-paliativos')->middleware('superEditor');
    Route::get('editarbasepaliativos/{id}', 'Paliativos\BasePaliativosController@show')->name('editarbasepaliativos')->middleware('superEditor');
    Route::put('actualizar-basepaliativos/{id}', 'Paliativos\BasePaliativosController@update')->name('actualizar-paliativos')->middleware('superEditor');
    Route::put('actualizarpro/{id}', 'Paliativos\BasePaliativosController@actualizarpro')->name('actualizarpro')->middleware('superEditor');
    Route::put('actualizarpaciente/{id}', 'Paliativos\BasePaliativosController@actualizarpaciente')->name('actualizarpaciente')->middleware('superEditor');
    
    Route::put('actualizarestado/{id}', 'Paliativos\BasePaliativosController@actualizarestado')->name('actualizarestado')->middleware('superEditor');
    Route::put('actualizarfallecido/{id}', 'Paliativos\BasePaliativosController@actualizarfallecido')->name('actualizarfallecido')->middleware('superEditor');
    
    
    Route::get('select_pro', 'Paliativos\BasePaliativosController@selectpro')->name('select_pro');
    Route::get('select_zona', 'Paliativos\BasePaliativosController@selectzona')->name('select_zona');
    Route::get('select_pac', 'Paliativos\BasePaliativosController@selectpac')->name('select_pac');

    //RUTA PARA INFORMES DE PALIATIVOS
    Route::get('informes-paliativos', 'Paliativos\BasePaliativosController@informes')->name('informespaliativos')->middleware('superEditor');

    //RUTA PARA OBSERVACIONES DE PALIATIVOS
    Route::get('obspaliativos-index', 'Paliativos\ObsPaliativosController@index')->name('indexobspaliativos')->middleware('superEditor');
    Route::post('crearobspaliativos', 'Paliativos\ObsPaliativosController@store')->name('crearobspaliativos')->middleware('superEditor');
    Route::get('editarobspaliativos/{id}', 'Paliativos\ObsPaliativosController@show')->name('editarobspaliativos')->middleware('superEditor');
    Route::put('actualizarobspaliativos/{id}', 'Paliativos\ObsPaliativosController@update')->name('actualizarobspaliativos')->middleware('superEditor');
    Route::put('eliminaralerta/{id}', 'Paliativos\ObsPaliativosController@deletealert')->name('eliminaralerta')->middleware('superEditor');

    Route::get('hospi', 'Paliativos\ObsPaliativosController@index_hospi')->name('hospi')->middleware('superEditor');
    Route::post('hospi', 'Paliativos\ObsPaliativosController@index_hospi1')->name('hospi1')->middleware('superEditor');

    //RUTA PARA LISTAS DE PALIATIVOS

    Route::get('/listas-index', 'Paliativos\Listas\ListasController@index')->name('listasIndex')->middleware('superEditor');
    Route::post('/crear-listas', 'Paliativos\Listas\ListasController@store')->name('crearlistas')->middleware('superEditor');
    Route::get('/editar-listas/{id}', 'Paliativos\Listas\ListasController@show')->name('editar-listas')->middleware('superEditor');
    Route::put('/actualizar-listas/{id}', 'Paliativos\Listas\ListasController@update')->name('actualizar-listas')->middleware('superEditor');
    Route::delete('/borrar-listas/{id}', 'Paliativos\Listas\ListasController@destroy')->name('borrar-listas')->middleware('superEditor');

    Route::post('/listas-estado', 'Paliativos\Listas\ListasController@updateestado')->name('lisestado')->middleware('superEditor');

    //RUTA PARA LISTAS DETALLE DE PALIATIVOS

    Route::get('/detallelistas', 'Paliativos\Listas\ListasDetalleController@indexDetalle')->name('listasdetalledetalle')->middleware('superEditor');
    Route::post('/detallecrear-listas', 'Paliativos\Listas\ListasDetalleController@store')->name('crearlistasdetalle')->middleware('superEditor');
    Route::get('/detalleeditar-listas/{id}', 'Paliativos\Listas\ListasDetalleController@show')->name('editar-listasdetalle')->middleware('superEditor');
    Route::put('/detalleactualizar-listas/{id}', 'Paliativos\Listas\ListasDetalleController@update')->name('actualizar-listasdetalle')->middleware('superEditor');
    Route::delete('/detalleborrar-listas/{id}', 'Paliativos\Listas\ListasDetalleController@destroy')->name('borrar-listasdetalle')->middleware('superEditor');

    Route::post('/detalle-estado', 'Paliativos\Listas\ListasDetalleController@updateestado')->name('detestado')->middleware('superEditor');


    //RUTAS PARA CARGAR ARCHIVOS IMPORT
    Route::get('archivos', 'Paliativos\EstadosController@index')->name('archivos')->middleware('superEditor');
    Route::post('subir_archivo', 'Paliativos\EstadosController@import')->name('subirarchivo')->middleware('superEditor');
    Route::post('subir_archivo_pac', 'Paliativos\BasePaliativosController@import')->name('subirarchivopac')->middleware('superEditor');
    Route::post('subir_archivo_ultpe', 'Paliativos\CosultaspeController@import')->name('subirarchivoupe')->middleware('superEditor');
    Route::post('subir_archivo_ultauxiliar', 'Paliativos\ConsultaAuxiliarController@import')->name('subirarchivouau')->middleware('superEditor');
    Route::post('subir_archivo_amb', 'Paliativos\BasePaliativosController@importambito')->name('subirarchivoamb')->middleware('superEditor');
    Route::post('subir_archivo_eva', [FidemContigoController::class, 'importeva'])->name('subirarchivoeva')->middleware('superEditor');

    //SELECT DE LISTAS

    route::get('selectlist', 'Paliativos\Listas\ListasDetalleController@select')->name('selectlist')->middleware('superEditor');
    
    //RUTAS PARA CARGAR NOVEDADES FIRTS
    
    Route::get('createnovedad', 'Nomina\NovedadesfirtsController@index')->name('createnovedad')->middleware('superEditor')->middleware('superEditor');
    Route::post('createnovedad1', 'Nomina\NovedadesfirtsController@index1')->name('createnovedad1')->middleware('superEditor')->middleware('superEditor');
    Route::post('save_createnovedad', 'Nomina\NovedadesfirtsController@store')->name('save_createnovedad')->middleware('superEditor');
    
    Route::get('fidemcontigo', [FidemContigoController::class, 'index'])->name('fidemcontigo.index1')->middleware('superAnalista');
    Route::post('fidemcontigo1', [FidemContigoController::class, 'indexFidem'])->name('fidemcontigo.indexfidem1')->middleware('superAnalista');
    Route::post('fidemcontigo_segui', [FidemContigoController::class, 'indexFidem_segui'])->name('fidemcontigo.indexfidems')->middleware('superAnalista');
    Route::post('fidemcontigo_sincon', [FidemContigoController::class, 'indexFidem_sincon'])->name('fidemcontigo.indexfidemsc')->middleware('superAnalista');
    Route::post('fidemcontigo_priorizados', [FidemContigoController::class, 'indexFidem_priorizados'])->name('fidemcontigo.indexfidempri')->middleware('superAnalista');
    Route::get('addseguimiento_fidemcontigo/{id}', [FidemContigoController::class, 'addseguimiento'])->name('fidemcontigo.addseguimiento')->middleware('superAnalista');
    Route::post('addseguimiento_fidemcontigo', [FidemContigoController::class, 'store'])->name('seguimiento.store')->middleware('superAnalista');
    Route::get('consultar_addseguimiento_fidemcontigo/{id}', [FidemContigoController::class, 'consultar_addseguimiento'])->name('fidemcontigo.consultar.addseguimiento')->middleware('superAnalista');
   
    
    
    Route::post('fidemcontigo/analista', [FidemContigoController::class, 'indexAnalista'])->name('fidemcontigo.analista1');
    Route::post('fidemcontigo/consulta', [FidemContigoController::class, 'indexConsulta'])->name('fidemcontigo.consulta1');
    Route::post('fidemcontigo/informe', [FidemContigoController::class, 'indexInforme'])->name('fidemcontigo.informe1');
    
        Route::prefix('fidemcontigo')->group(function () {
        Route::get('/',[FidemContigoController::class, 'index'])->name('fidemcontigo.index');
        Route::get('/analista', [FidemContigoController::class, 'indexAnalista'])->name('fidemcontigo.analista');
        Route::get('/consulta', [FidemContigoController::class, 'indexConsulta'])->name('fidemcontigo.consulta');
        Route::get('/informe', [FidemContigoController::class, 'indexInforme'])->name('fidemcontigo.informe');
    });
    
      //RUTA PARA INFORMES DE FIDEMCONTIGO
    Route::get('informes-fidemcontigo', [FidemContigoController::class, 'informes'])->name('informesfidemcontigo')->middleware('superEditor');
    
    //RUTA PARA ENCUESTAS DE FISIATRIA
    
    
    Route::get('index-encuestas', 'EncuestaFisiatria\EncuestaFisiatriaController@index')->name('fisiatria1')->middleware('superPsicologica');
    Route::post('index-encuestas', 'EncuestaFisiatria\EncuestaFisiatriaController@index1')->name('fisiatria1')->middleware('superPsicologica');
    Route::post('guardar_encuesta', 'EncuestaFisiatria\EncuestaFisiatriaController@store')->name('guardar_encuesta')->middleware('superPsicologica');
    Route::get('encuesta/{id}', 'EncuestaFisiatria\EncuestaFisiatriaController@show')->name('detalleEvolucion_f')->middleware('superPsicologica');
    Route::put('anular_fisiatria/{id}', 'EncuestaFisiatria\EncuestaFisiatriaController@anular')->name('anular_fisiatria')->middleware('superPsicologica');
    
    Route::get('informe-fisi', 'EncuestaFisiatria\EncuestaFisiatriaController@informePsico')->name('informefisi')->middleware('superPsicologica');
    Route::get('consultar_procedimiento_fisiatria', 'EncuestaFisiatria\EncuestaFisiatriaController@indexProcedimiento')->name('consultaprocedimiento_f')->middleware('superPsicologica');
    Route::post('consultar_procedimiento_table_fisiatria', 'EncuestaFisiatria\EncuestaFisiatriaController@indexProcedimientotable')->name('consultaprocedimientotable_f')->middleware('superPsicologica');
    
    
    Route::get('consultar_evolucion_f', 'EncuestaFisiatria\EncuestaFisiatriaController@indexAnalista')->name('analistapsico_f')->middleware('superPsicologica');
    Route::get('consultar_evoluciona_f', 'EncuestaFisiatria\EncuestaFisiatriaController@indexAnalistaa')->name('analistapsicoa_f')->middleware('superPsicologica');
    Route::get('consultar_evolucions_f', 'EncuestaFisiatria\EncuestaFisiatriaController@indexAnalistas')->name('analistapsicos_f')->middleware('superPsicologica');
    
    Route::post('consultar_evolucion1_f', 'EncuestaFisiatria\EncuestaFisiatriaController@indexAnalista1')->name('analistapsico1_f')->middleware('superPsicologica');
    Route::post('consultar_evoluciona1_f', 'EncuestaFisiatria\EncuestaFisiatriaController@indexAnalistaa1')->name('analistapsicoa1_f')->middleware('superPsicologica');
    Route::post('consultar_evolucions1_f', 'EncuestaFisiatria\EncuestaFisiatriaController@indexAnalistas1')->name('analistapsicos1_f')->middleware('superPsicologica');
    
    Route::get('evolucion_f/{id}', 'EncuestaFisiatria\EncuestaFisiatriaController@detalleEvolucion')->name('detalleEvolucion_f')->middleware('superPsicologica');
    Route::get('addseguimiento_f/{id}', 'EncuestaFisiatria\EncuestaFisiatriaController@addseguimiento')->name('addseguimiento_f')->middleware('superAnalista');

    Route::post('guardar_observacion_f', 'EncuestaFisiatria\ObservacionesPsicologiaController@store')->name('guardar_observacion_f')->middleware('superPsicologica');

    Route::get('agendadopro_f/{id}', 'EncuestaFisiatria\EncuestaFisiatriaController@agendadoEvolucionPro')->name('agendadoEvolucionPro_f')->middleware('superAnalista');
    Route::post('agendado_f', 'EncuestaFisiatria\EncuestaFisiatriaController@agendadoEvolucion')->name('agendadoEvolucion_f')->middleware('superAnalista');
    
     Route::get('consultardocumento_f', 'EncuestaFisiatria\EncuestaFisiatriaController@consultarDocumento')->name('consultardocumento_f')->middleware('superPsicologica');
     
     Route::put('anular_evolucion/{id}', 'EncuestaFisiatria\EncuestaFisiatriaController@anularEvolucion')->name('anular_evolucion_f')->name('analistapsico')->middleware('superPsicologica');


    
});

<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});

Route::post('medidoresout','Admin\OrdenesmtlasignarController@medidorall');
Route::post('medidores','Admin\OrdenEjecutadaController@medidorejecutado');
Route::post('marcas','Admin\MarcasController@marcasall');
Route::post('loginMovil1','Seguridad\LoginController@loginMovil');

/*
|--------------------------------------------------------------------------
| Chat API Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('chat')->group(function () {
    // Chats
    Route::get('/', 'Chat\ChatController@index');
    Route::post('/', 'Chat\ChatController@store');
    Route::get('/{id}', 'Chat\ChatController@show');
    Route::delete('/{id}', 'Chat\ChatController@destroy');
    Route::post('/find-or-create-patient-chat', 'Chat\ChatController@findOrCreatePatientChat');

    // Mensajes
    Route::get('/{chatId}/messages', 'Chat\ChatMessageController@index');
    Route::post('/{chatId}/messages', 'Chat\ChatMessageController@store');
    Route::get('/{chatId}/messages/poll', 'Chat\ChatMessageController@poll');
});

/*
|--------------------------------------------------------------------------
| Paciente API Routes (Para Claude AI y búsquedas)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('pacientes')->group(function () {
    // Buscar paciente por documento con historias clínicas
    Route::get('/buscar-documento', 'Api\PacienteApiController@buscarPorDocumento');

    // Obtener contexto completo para Claude AI
    Route::get('/contexto-claude', 'Api\PacienteApiController@obtenerContextoClaude');
});

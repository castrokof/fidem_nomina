<?php

/*
|--------------------------------------------------------------------------
|  RUTAS API - Boxalud Plugin
|  Archivo: app/Http/routes.php
|  Laravel 5.1
|--------------------------------------------------------------------------
|
|  IMPORTANTE: Pegar estas rutas dentro del archivo routes.php existente.
|  No reemplazar el archivo completo — solo agregar este bloque al final.
|
*/

Route::group([
    'prefix'     => 'api',
    'middleware' => ['cors', 'auth:api'],
], function () {

    // ── Guardar nueva consulta (desde el plugin Chrome) ──────────────────────
    Route::post('validaciones-boxalud',
        'Api\ValidacionBoxaludController@store');

    // ── Consultas del día actual ──────────────────────────────────────────────
    Route::get('validaciones-boxalud/hoy',
        'Api\ValidacionBoxaludController@hoy');

    // ── Verificar duplicado del día ───────────────────────────────────────────
    Route::get('validaciones-boxalud/existe-hoy/{documento}',
        'Api\ValidacionBoxaludController@existeHoy');

    // ── Historial de un documento ─────────────────────────────────────────────
    Route::get('validaciones-boxalud/historial/{documento}',
        'Api\ValidacionBoxaludController@historial');

    // ── Reporte por rango de fechas ───────────────────────────────────────────
    // Parámetros opcionales: ?desde=2026-05-01&hasta=2026-05-31
    Route::get('validaciones-boxalud/reporte',
        'Api\ValidacionBoxaludController@reporte');

    // ── Detalle de una consulta ───────────────────────────────────────────────
    Route::get('validaciones-boxalud/{id}',
        'Api\ValidacionBoxaludController@show');

    // ── Descargar screenshot de una consulta ──────────────────────────────────
    Route::get('validaciones-boxalud/{id}/screenshot',
        'Api\ValidacionBoxaludController@descargarScreenshot');

});

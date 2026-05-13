<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Orígenes permitidos para consumir la API.
     * Agregar la URL de la extensión Chrome y cualquier otro origen necesario.
     */
    protected $allowedOrigins = [
        // Extensión de Chrome (el origen tiene este formato)
        //Jhonnathan 
        'chrome-extension://dhllefiaekbnhbknifllagidckkekinb', 
        //Miguel
        'chrome-extension://ncagaplaicedgoalflgjlaeolggpchnn',
       //Laura Pulgarin
       'chrome-extension://eomdihhehkmoelgfppanhjapbgaijldg',

       
    ];

    public function handle($request, Closure $next)
    {
        // Manejar preflight OPTIONS
        if ($request->isMethod('OPTIONS')) {
            return $this->buildResponse($request, response('', 204));
        }

        $response = $next($request);

        return $this->buildResponse($request, $response);
    }

    protected function buildResponse($request, $response)
    {
        $origin = $request->header('Origin');

        // Permitir el origen si está en la lista blanca
        if ($origin && in_array($origin, $this->allowedOrigins)) {
            $response->header('Access-Control-Allow-Origin', $origin);
        } else {
            // En desarrollo puedes usar * temporalmente:
            // $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Access-Control-Allow-Origin', '');
        }

        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Requested-With');
        $response->header('Access-Control-Allow-Credentials', 'true');
        $response->header('Access-Control-Max-Age', '86400'); // Cache preflight 24h

        return $response;
    }
}

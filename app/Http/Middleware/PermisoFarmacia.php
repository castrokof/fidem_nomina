<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermisoFarmacia
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
         if ((session()->get('rol_nombre') == ('administrador')) || (session()->get('rol_nombre') == ('farmacia')) || (session()->get('rol_nombre') == ('analista')))
        {

        return $next($request);

         }

        abort(403, "No tienes autorización para ingresar.");
    }
}

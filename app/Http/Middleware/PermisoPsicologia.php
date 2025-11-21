<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermisoPsicologia
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

        if ((session()->get('rol_nombre') == ('supervisor')) || (session()->get('rol_nombre') == ('contratista')) || (session()->get('rol_nombre') == ('administrador')) || (session()->get('rol_nombre') == ('creador_procedimientos'))  || (session()->get('rol_nombre') == ('analista')))
        return $next($request);

        abort(403, "No tienes autorización para ingresar.");

        return $next($request);
    }
}

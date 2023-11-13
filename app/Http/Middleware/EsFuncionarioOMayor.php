<?php

namespace App\Http\Middleware;

use App\Models\PersonaRol;
use Closure;
use Illuminate\Http\Request;

class EsFuncionarioOMayor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $rol = PersonaRol::findOrFail($request->user()->id)->rol;
        $rolesPermitidos = ["funcionario", "administrador", "gerente"];
        if(!in_array($rol, $rolesPermitidos))
            return response([
                "message" => "No tienes permiso para ver esto."
            ], 401);
        return $next($request);
    }
}

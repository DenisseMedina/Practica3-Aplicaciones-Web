<?php

namespace App\Http\Middleware;

use Closure;

class VerificaUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $usuarioVerificacion = \App\User::where('email',$request->email)->first();
        if ($usuarioVerificacion) {
           if($usuarioVerificacion->email_verified_at == NULL){
               return abort (401,'No tienes activada tu cuenta, Sorry.');
           }
        }
        return $next($request);
    }
}

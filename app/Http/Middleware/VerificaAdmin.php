<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class VerificaAdmin
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
        if($request->user()->tokenCan('Usuario')){
            $usuario = $request->user()->email;
            $url = $request->url();
            verificaAdmin::email($usuario,$url);
            return abort(401);
        }
        return $next($request);
    }
    public static function email($usuario, $url){
        $Administradores= DB::table('users')->select('email')->where('rol','=','Admin')->get();
        foreach ($Administradores as $admin) {
            $data = array(
            'usuario' => $usuario,
            'url' => $url
            );
            Mail::send('verificacion', $data, function ($message) use ($admin){
                $message->from('denissemedina216@gmail.com', 'No Autorizado');
                $message->to($admin->email)->subject('Usuario no autorizado.');
            });
        }
    }
}

<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function registro(Request $request) {

        $request->validate([
            'email'=> 'required|email',
            'password' =>'required',
        ]); 

        $usuario = new User();
        if($request->hasFile('file')){
            $path = Storage::disk('public')->putFile('perfiles/img',$request->file);
            $usuario ->imagen=$path;
            $usuario ->email=$request->email;
            $usuario->password= Hash::make($request->password);
            $usuario ->rol=$request->rol;
            $usuario ->persona_id=$request->persona_id;
            $usuario ->save();
            UserController::send($request);
            return response()->json($usuario,201);
        }  
        return abort(400,"No se puede Registar, Intentelo Nuevamente");
    }    

    public static function send(request $request){
        $data = array(
            'correo' => $request->email
        );
            Mail::send('email', $data, function ($message) use ($request){
            $message->from('denissemedina216@gmail.com', 'Correo de Verificación');
            $message->to($request->email)->subject('Confirma tu email');
        });
        return "Email enviado";
    }

    public function verificarCorreo(string $correo){
        $usuarioVerificacion = User::where('email',$correo)->first();
        if($usuarioVerificacion){
            $usuarioVerificacion->email_verified_at = NOW();
            $usuarioVerificacion->save();
            return response()->json(["Usuario Verificado",200]); 
        }
        return abort ('Error, Intentalo Nuevamente',401);
    }

    public function logIn(Request $request){
        $request->validate([
            'email'=> 'required|email',
            'password'=>'required',
        ]);

        $usuario = User::where('email', $request->email)->first();

        if(! $usuario || ! Hash::check($request->password, $usuario->password))
        {
            throw ValidationException::withMessages([
                'email' => ['Datos Incorrectos']
            ]);
        }
        //$usuario->rol=$request->rol=='Usuario'
        //->rol=='Usuario'
        if($usuario->rol=='Usuario'){
            $token = $usuario->createToken($request->email,['Usuario'])->plainTextToken;
        }
        elseif($usuario->rol=='Admin'){
            $token = $usuario->createToken($request->email,['Admin'])->plainTextToken;
        }
        return response()->json(["Token"=>$token],201);
    }

    public function salir(Request $request){
        return response()->json(["Eliminados"=>$request->user()->tokens()->delete()],200);
    }

    public function inicio(Request $request){
        if($request->user()-> tokenCan('Usuario'))
        {
            return response()->json(["Perfil de Usuario"=>$request->user()],200);
        }
        elseif($request->user()-> tokenCan('Admin'))
        {
            return response()->json(["Perfil de Administador"=>$request->user()],200);
        }
        return abort(401,"Invalido");
    } 

    public function darPermisos(Request $request)
    {
    $usuario=user::find($request->id);
    $usuario->rol=='Admin';
    $token =$usuario->createToken($usuario->email,['Admin']);
    if($usuario->save()){
        return response()->json(["Cambios Realizados"=>user::find($request->id),"Nuevo token"=>$token],200);
    }
   }

   public function mostrar(){
    $usuarios = user::all();
    return response()->json($usuarios,200);
   } 

   public function fotoPerfil(Request $request){   
    if($request->hasFile('file')){ 
    //$extension = $request->file('file')->extension();
    $path = Storage::disk('public')->putFile('perfiles/img',$request->file);
    }
    return response()->json(["Cargado Exitosamente"=>$path],201);
   }

}



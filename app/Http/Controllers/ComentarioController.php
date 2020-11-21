<?php

namespace App\Http\Controllers;

use App\Comentario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function comentarios(Request $request){

        $insertaComentario = new \App\Comentario;
        $insertaComentario->comentario = $request->comentario;
        //$insertaComentario->user_id = $request->user_id;
        $correo = $request->user()->email;
        $usuario_id = DB::table('users')->select('id')->where('email','=',$correo)->first();
        $insertaComentario->user_id = $usuario_id->id;
        $insertaComentario->publicacion_id = $request->publicacion_id;   
        $insertaComentario->save();

        if($insertaComentario->save()){
            ComentarioController::correoPub($request->publicacion_id,$correo,$request->comentario);
            ComentarioController::correoComentario($request->publicacion_id,$correo,$request->comentario);
            return response()->json(["Comentario" => Comentario::find($insertaComentario->id)]);
        }
    }

    public static function correoPub($publicacion,$comentario, $email){
        $datosPub = DB::table('publicacions')->where('id','=',$publicacion)->first();
        $usuarioCom = DB::table('users')->select('email')->where('id','=',$datosPub ->user_id)->first();
        $data = array(
            'titulo'=> $datosPub ->titulo,
            'texto'=> $datosPub ->texto,
            'comentario'=> $comentario,
            'email'=> $email
            );
            Mail::send('comentario', $data, function ($message) use ($usuarioCom){
                $message->from('denissemedina216@gmail.com', 'Se realizo un comentario');
                $message->to($usuarioCom->email)->subject('Se realizo un comentario');
            });
        }

    public static function correoComentario($publicacion, $comentario,$email){
        $datosPub = DB::table('publicacions')->where('id','=',$publicacion)->first();
        $usuarioCom = DB::table('users')->select('email')->where('id','=',$datosPub ->user_id)->first();
        $data = array(
            'titulo'=> $datosPub ->titulo,
            'comentario'=>$comentario,
            'email'=> $email
            );
            Mail::send('comentarioDos', $data, function ($message) use ($usuarioCom){
                $message->from('denissemedina216@gmail.com', 'Realizaste un comentario.');
                $message->to($usuarioCom->email)->subject('Realizaste un comentario.');
            });
        }

    public function buscar(int $id=0){

        return response()->json([
        "comentario"=> ($id == 0)? \App\Comentarios::all():\App\Comentarios::find($id)],200);
    } 

    public function modificar(int $id, string $comentario){
        $modificarComentario = \App\Comentarios::find($id);
        $modificarComentario->comentario = $comentario;
        $modificarComentario->save();
        return response()->json(["comentario actualizado",],200);
    }

    public function eliminar(int $id){
        $eliminarComentario = \App\Comentarios::find($id);
        $eliminarComentario->delete();
        return response()->json(["comentario eliminado","comentario" => \App\Comentarios::all() ],200);
    }
   
    public function comentarioPersona(int $usuario_id,int $id=null ){
        return response()->json([
        'persona'=>( $id==null)? 
        Comentarios::where('usuario_id', $usuario_id)->get():
        Comentarios::where('usuario_id', $usaurio_id)->where('id',$id)->get()],200);
    }

    public function comentarioPublicacion( int $pub_id, int $id=null ){
         return response()->json([
        'publicaciones'=>( $id==null)? 
        Comentarios::where('publicacion_id', $pub_id)->get():
        Comentarios::where('publicacion_id', $pub_id)->where('id',$id)->get()],200);
    }

    public function comentarioPublicacionPersona(int $usuario_id, int $publicacion_id, int $id=null){
        return response()->json([
        'persona'=>( $id==null)? 
        Comentarios::where('usuario_id', $usuario_id)->where('publicacion_id', $publicacion_id)->get():
        Comentarios::where('usuario_id', $usuario_id)->where('publicacion_id', $publicacion_id)->where('id',$id)->get()],200);
    }

    public function todaBaseDatos(){
        return response()->json([
        'Toda la Base de Datos'=>DB::table('comentarios')
        ->join('publicaciones','publicaciones.id', '=', 'comentarios.publicacion_id')
        ->join ('personas','personas.id','=', 'comentarios.persona_id')
        ->select('comentarios.*', 'publicaciones.*','personas.*')->get()],200);
    }

 
}

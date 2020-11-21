<?php

namespace App\Http\Controllers;

use App\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class PublicacionController extends Controller
{
    public function publicaciones(Request $request){
        if($request->hasFile('file')){
        $path = Storage::disk('public')->putFile('publicaciones/img',$request->file);
        $insertaPublicacion = new \App\Publicacion;
        $insertaPublicacion->titulo = $request->titulo;
        $insertaPublicacion->texto = $request->texto;
        $insertaPublicacion->imagen=$path;
        //$insertaPublicacion->user_id = $request->user_id;   
        //$insertaPublicacion->save();
            //return response()->json (["Insertar Publicacion",],201);
        }
        //return abort(400,"Error");
        $correo = $request->user()->email;
        $usuario_id = DB::table('users')->select('id')->where('email','=',$correo)->first();
        $insertaPublicacion->user_id = $usuario_id->id;
        $insertaPublicacion->save();

        return response()->json(["publicacion" => Publicacion::find($insertaPublicacion->id)],201);
    }
    
    public function buscar(Request $request){
        return response()->json(["Request" =>$request ->all(),
            "publicacion"=> ($request->$id == 0)? \App\Publicacion::all():\App\Publicacion::find($request->id)],200);
    } 

    public function modificarTitulo (int $id, string $titulo){
        $modificarTitulo = \App\Publicacion::find($id);
        $modificarTitulo->titulo = $titulo;
        $modificarTitulo->save();
        return response()->json([
        "Titulo Actualizado",
        "publicacion" => \App\Publicacion::find($id)],200);
    }

    public function modificarTexto (int $id, string $texto){
        $modificarTexto = \App\Publicacion::find($id);
        $modificarTexto->texto = $texto;
        $modificarTexto->save();
        return response()->json([
        "Texto Actualizado",
        "publicacion" => \App\Publicacion::find($id)],200);
    }

    public function eliminar(int $id){
        $eliminarPublicacion = \App\Publicacion::find($id);
        $eliminarPublicacion->delete();
        return response()->json([
        "Publicacion Eliminada",
        "Publicacion" => \App\Publicacion::all()],200);
    }

    public function pubPersona(int $usaurio, int $publicacion = null){
        return response()->json([
        "publicaciones realizadas"=>($publicacion == null)?\App\Publicacion::where('usaurio_id', $persona)
        ->get():\App\Publicacion::where('usaurio_id', $usaurio)->where('id',$publicacion)->get()],200);
    }

    public function fotoPub(Request $request){   
        if($request->hasFile('file')){ 
        //$extension = $request->file('file')->extension();
        $path = Storage::disk('public')->putFile('publicaciones/img',$request->file);
       }
    return response()->json(["Cargado Exitosamente"=>$path],201);
   }

}

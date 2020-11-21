<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//-------------------------------------------------RutasDePersonasPruebas------------------------------------------//
Route::get('/persona/registro','PersonaController@persona')->middleware('verifica.edad');
Route::put('/modificar/nombre','PersonaController@modificarNombre');
Route::put('/modificar/apellidopaterno','PersonaController@modificarApellidoPaterno');
Route::put('/modificar/apellidomaterno','PersonaController@modificarApellidoMaterno');
Route::put('/modificar/edad','PersonaController@modificarEdad');
Route::put('/modificar/sexo','PersonaController@modificarSexo');

Route::get('verificacion/{correo}','UserController@verificarCorreo'); //Verificacion de cuenta
Route::post('/imagen/perfil','UserController@fotoPerfil'); //Subir foto de Perfil de Usuario
Route::post('/imagen/publicacion','PublicacionController@fotoPub'); //Subir foto de Publicacion

//Registro & Login de Usuario
Route::post('registro', 'UserController@registro'); 
Route::post('login', 'UserController@logIn')->middleware('verifica.usuario');

Route::middleware('auth:sanctum')->get('usuario/inicio','UserController@inicio'); //Inicio 
Route::middleware('auth:sanctum')->delete('salir','UserController@salir'); //Salir o Cerrar Sesión

//-------------------------------------------------RutasDeAdmin------------------------------------------//
Route::middleware(['auth:sanctum', 'verifica.admin'])->group(function (){
Route::get('/persona/buscar', 'PersonaController@buscar'); //Buscar Personas
Route::delete('/personas/eliminar', 'PersonaController@eliminar'); //Eliminar Personas

Route::post('permisos', 'UserController@darPermisos'); //Dar Permisos
Route::get('/mostrar/usuarios', 'UserController@mostrar'); //Mostar a los Usuarios 

Route::get('personas/publicaciones/comentarios','ComentarioController@todaBaseDatos'); //Buscar toda la base de datos

//-------------------------------------------------Publicaciones------------------------------------------//
Route::post('/publicacion/nueva','PublicacionController@publicaciones'); //Nueva Publicacion
Route::get('/publicaciones/{id?}', 'PublicacionController@buscar')->where(['id','[0-9]+']); //Buscar Publicaciones

Route::put('/actualizar/publicacion/{id?}/titulo/{titulo}', 'PublicacionController@modificarTitulo') 
->where(['id'=>'[0-9]+','titulo'=>'[a-zA-Z]+',]); //ActualizarPublicaciones
Route::put('/actualizar/publicacion/{id?}/texto/{texto}', 'PublicacionController@modificarTexto')
->where(['id'=>'[0-9]+','texto'=>'[a-zA-Z]+',]); //ActualizarPublicaciones

Route::delete('/eliminar/publicaciones/{id}', 'PublicacionController@eliminar')->where(['id'=>'[0-9]+']); //EliminarPublicaciones

//-------------------------------------------------Comentarios------------------------------------------//
Route::post('/comentarios','ComentarioController@comentarios'); //InsertarComentarios
Route::get('/buscar/{id?}', 'ComentarioController@buscar')->where(['id','[0-9]+']);  //BuscarComentarios
Route::put('/actualizar/{id?}/comentario/{comentario}', 'ComentarioController@modificar')
->where(['id'=>'[0-9]+','texto'=>'[a-zA-Z]+',]);//ActualizarComentario
Route::delete('/eliminar/comentarios/{id}', 'ComentarioController@eliminar')->where(['id'=>'[0-9]+']); //EliminarComentarios
});


Route::middleware(['auth:sanctum'])->group(function () {
Route::get('/persona/registro','PersonasController@persona')->middleware('verificar.edad');
Route::put('/modificar/nombre','PersonasController@modificarNombre');
Route::put('/modificar/apellidopaterno','PersonasController@modificarApellidoPaterno');
Route::put('/modificar/apellidomaterno','PersonasController@modificarApellidoMaterno');
Route::put('/modificar/edad','PersonasController@modificarEdad');
Route::put('/modificar/sexo','PersonasController@modificarSexo');
});
    
//-------------------------------------------------API------------------------------------------//
Route::get('/apiconsumo/color','ApiConsumoController@color');
Route::get('/apiconsumo/category','ApiConsumoController@category');
Route::get('/apiconsumo/ability','ApiConsumoController@ability');




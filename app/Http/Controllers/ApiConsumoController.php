<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ApiConsumoController extends Controller
{
    public function category(Request $request){
        $response = Http::get('https://pokeapi.co/api/v2/generation/'.$request->name);
        return $response->json();
    }
    public function color(Request $request){
        $response = Http::get('https://pokeapi.co/api/v2/pokemon/'.$request->id);
        return $response->json();
    }

    public function ability(Request $request){
        $response = Http::get('https://pokeapi.co/api/v2/ability/'.$request->name);
        return $response->json();
    }
}

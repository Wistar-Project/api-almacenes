<?php

namespace App\Http\Controllers;

use App\Models\SedeHogar;
use App\Models\Paquete;
use Illuminate\Http\Request;

class PaqueteController extends Controller
{
    public function ListarPaquetes(){
        return Paquete::all();
    }

    public function CrearPaquete(Request $request){
        $pesoEnKilogramos = $request -> post("pesoEnKilogramos");
        $destino = $request -> post("destino");
        $sedeOHogarEncontrado = SedeHogar::find($destino);
        if($sedeOHogarEncontrado == null){
            $BAD_REQUEST_HTTP= 400;
            abort($BAD_REQUEST_HTTP, "La sede u hogar ingresada no existe");
        }
        if(!isset($pesoEnKilogramos)){
            $BAD_REQUEST_HTTP= 400;
            abort($BAD_REQUEST_HTTP, "No se ha ingresado un peso");
        }
        Paquete::create([
            "peso_en_kg" => $pesoEnKilogramos,
            "destino" => $destino
        ]);
    }

}

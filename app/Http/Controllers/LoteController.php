<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Paquete;
use App\Models\LoteFormadoPor;
use App\Models\Lote;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function ListarLotes(){
        return Lote::all();
    }

    public function CrearLote(Request $request){
        $destino = $request -> post("destino");
        $sedeEncontrada = Sede::find($destino);
        if($sedeEncontrada == null){
            $BAD_REQUEST_HTTP= 400;
            abort($BAD_REQUEST_HTTP, "La sede no existe");
        }
        Lote::create([
            "destino" => $destino 
        ]);
    }

    public function AsignarPaquete(Request $request){
        $lote = $request -> post("lote");
        $paquete = $request -> post("paquete");
        $loteEncontrado = Lote::find($lote); 
        $paqueteEncontrado = Paquete::find($paquete);
        if($loteEncontrado == null || $paqueteEncontrado == null){
            $BAD_REQUEST_HTTP = 400;
            abort($BAD_REQUEST_HTTP, "El lote o paquete ingresado no existe");
        }
        LoteFormadoPor::create([
            "id_lote" => $lote,
            "id_paquete" => $paquete
        ]);
    }
}

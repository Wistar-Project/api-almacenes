<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Paquete;
use App\Models\LoteFormadoPor;
use App\Models\Lote;
use Illuminate\Http\Request;
use App\Models\Alojamiento;
use App\Models\LoteAsignadoACamion;

class LoteController extends Controller
{
    public function MostrarLote($id){
        $lote = Lote::findOrFail($id);
        $destino = Alojamiento::find($lote -> destino) -> direccion;
        $paquetesEnLote = LoteFormadoPor::where('id_lote', '=', $id) -> get();
        $cantidadPaquetes = count($paquetesEnLote);
        $pesoEnKg = 0;
        foreach($paquetesEnLote as $paqueteEnLote){
            $paquete = Paquete::find($paqueteEnLote -> id_paquete);
            $pesoEnKg += $paquete -> peso_en_kg;
        }
        $camionAsignado = LoteAsignadoACamion::find($id);
        if($camionAsignado == null) $camionAsignado = "Ninguno";
        return [
            "id" => $id,
            "pesoEnKg" => $pesoEnKg,
            "camionAsignado" => $camionAsignado,
            "fechaModificacion" => $lote -> updated_at,
            "direccionDestino" => $destino,
            "cantidadPaquetes" => $cantidadPaquetes
        ];
    }

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

    public function MostarLotesParaAsignar(Request $request, $idDestino){
        $lotesConEseDestino = Lote::where('destino', $idDestino) -> get();
        return $lotesConEseDestino -> pluck('id');
    }
}

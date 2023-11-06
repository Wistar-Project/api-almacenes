<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Models\Paquete;
use App\Models\LoteFormadoPor;
use App\Models\Lote;
use Illuminate\Http\Request;
use App\Models\Alojamiento;
use App\Models\LoteAsignadoACamion;
use Illuminate\Support\Facades\Validator;

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
        return Lote::create([
            "destino" => $destino 
        ]);
    }

    public function AsignarPaquete(Request $request){
        $lote = Lote::findOrFail($request -> post("lote")); 
        $paquete = Paquete::findOrFail($request -> post("paquete"));
        $BAD_REQUEST_HTTP = 400;
        if($lote -> destino != $paquete -> destino){
            abort($BAD_REQUEST_HTTP, "Ambos deben tener el mismo destino");
        }
        if($paquete -> lote)
            return abort($BAD_REQUEST_HTTP, "El paquete ya está asignado a un lote");
        return LoteFormadoPor::create([
            "id_lote" => $request -> post('lote'),
            "id_paquete" => $request -> post('paquete')
        ]);
    }

    public function MostrarLotesParaAsignar(Request $request, $idDestino){
        $lotesConEseDestino = Lote::where('destino', $idDestino) -> get();
        return $lotesConEseDestino -> pluck('id');
    }
}

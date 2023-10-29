<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use App\Models\Alojamiento;
use App\Models\PaqueteAsignadoAPickup;
use Illuminate\Http\Request;
use App\Models\Sede;
use Illuminate\Support\Facades\Validator;

class PaqueteController extends Controller
{
    public function ListarPaquetes(){
        return Paquete::all();
    }
    public function verInformacionDeUnPaquete($id){
        $paquete = Paquete::findOrFail($id);
        $destino = Alojamiento::find($paquete -> destino) -> direccion;
        return [
            "id" => $id,
            "pesoEnKg" => $paquete -> peso_en_kg,
            "fechaModificacion" => $paquete -> updated_at,
            "direccionDestino" => $destino,
            "vehiculoAsignado" => $this -> obtenerVehiculoAsignado($paquete),
            "email" => $paquete -> email,
            "loteAsignado" => $paquete -> lote ?? "Ninguno"
        ];
    }

    private function obtenerVehiculoAsignado($paquete){
        if($paquete -> pickup){
            $idVehiculoAsignado = $paquete -> pickup -> id_pickup;
            return "Pickup $idVehiculoAsignado";
        }
        $loteAsignado = $paquete -> lote;
        if($loteAsignado && $loteAsignado -> camion){
            $idVehiculoAsignado = $loteAsignado -> camion -> id_camion;
            return "Camión $idVehiculoAsignado";
        }
        return "Ninguno";
    }
    public function CrearPaquete(Request $request){
        Validator::make($request-> all(), [
            'pesoEnKilogramos' => 'required|numeric',
            'email' => 'required|max:70',
            'destino' => 'required|exists:sedes'
        ]);
        
        Paquete::create([
            "peso_en_kg" => $request -> post("pesoEnKilogramos"),
            "destino" => $request -> post('destino'),
            "email" => $request -> post('email')
        ]);
    }

}

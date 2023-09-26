<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use App\Models\Alojamiento;
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
        $pesoEnKg = $paquete -> peso_en_kg;
        return [
            "id" => $id,
            "pesoEnKg" => $pesoEnKg,
            "fechaModificacion" => $paquete -> updated_at,
            "direccionDestino" => $destino,
        ];
    }
    public function CrearPaquete(Request $request){
        Validator::make($request-> all(), [
            'pesoEnKilogramos' => 'required|numeric',
            'email' => 'required|max:70',
            'destino' => 'required|exists:sedes'
        ]);
        
        Paquete::create([
            "peso_en_kg" => $request -> post("pesoEnKilogramos"),
            "destino" => $request -> post('destino')
        ]);
    }

}

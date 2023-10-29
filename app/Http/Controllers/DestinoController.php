<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sede;

class DestinoController extends Controller
{
    public function MostrarDestinos(){
        $destinosConDireccion = [];
        foreach (Sede::all() as $sede){
            array_push($destinosConDireccion, [
                "id" => $sede -> id,
                "direccion" => $sede -> alojamiento -> direccion
            ]);
        }
        return $destinosConDireccion;
    }
}

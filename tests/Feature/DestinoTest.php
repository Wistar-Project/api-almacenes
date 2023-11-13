<?php

namespace Tests\Feature;

use App\Models\Conductor;
use App\Models\Funcionario;
use App\Models\Persona;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestinoTest extends TestCase
{
    private function crearFuncionario(){
        $user = User::factory()->create();
        Persona::create([ "id" => $user -> id, "nombre" => "a", "apellido" => "a" ]);
        Funcionario::create(["id" => $user -> id]);
        return $user;
    }

    private function crearChofer(){
        $user = User::factory()->create();
        Persona::create([ "id" => $user -> id, "nombre" => "a", "apellido" => "a" ]);
        Conductor::create(["id" => $user -> id]);
        return $user;
    }

    public function test_obtener_destinos(){
        $destinosConDireccion = [];
        foreach (Sede::all() as $sede){
            array_push($destinosConDireccion, [
                "id" => $sede -> id,
                "direccion" => $sede -> alojamiento -> direccion
            ]);
        }
        $response = $this->actingAs($this -> crearFuncionario())->get("/api/v1/destinos");
        $response -> assertStatus(200);
        $response -> assertExactJson($destinosConDireccion);
    }

    public function test_obtener_destinos_sin_autenticarse(){
        $response = $this->get("/api/v1/destinos", [
            "Accept" => "application/json"
        ]);
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_obtener_destinos_siendo_chofer(){
        $response = $this->actingAs($this->crearChofer())->get("/api/v1/destinos", [
            "Accept" => "application/json"
        ]);
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }
}

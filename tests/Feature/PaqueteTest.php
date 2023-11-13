<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Conductor;
use App\Models\Funcionario;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Paquete;

class PaqueteTest extends TestCase
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

    private function crearAdministrador(){
        $user = User::factory()->create();
        Persona::create([ "id" => $user -> id, "nombre" => "a", "apellido" => "a" ]);
        Administrador::create(["id" => $user -> id]);
        return $user;
    }
    
    public function test_listar_paquetes()
    {
        $response = $this->actingAs($this -> crearFuncionario()) -> get('/api/v1/paquetes');
        $response->assertStatus(200);
        $response->assertExactJson(Paquete::all() -> toArray());
    }

    public function test_listar_paquetes_siendo_administrador()
    {
        $response = $this->actingAs($this -> crearAdministrador()) -> get('/api/v1/paquetes');
        $response->assertStatus(200);
        $response->assertExactJson(Paquete::all() -> toArray());
    }

    public function test_listar_paquetes_siendo_chofer()
    {
        $response = $this->actingAs($this -> crearChofer()) -> get('/api/v1/paquetes');
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_listar_paquetes_sin_autenticarse()
    {
        $response = $this -> get('/api/v1/paquetes', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_crear_paquete()
    {
        $response = $this->actingAs($this->crearAdministrador())->post('/api/v1/paquetes', [
            "pesoEnKilogramos" => 3,
            "destino" => 1,
            "email" => "test@test"
        ]);
        $response->assertStatus(201);
        $this -> assertDatabaseHas('paquetes', [
            "destino" => 1,
            "peso_en_kg" => 3,
            "email" => "test@test"
        ]);
    }

    public function test_crear_paquete_sin_autenticarse()
    {
        $response = $this->post('/api/v1/paquetes', [
            "pesoEnKilogramos" => 3,
            "destino" => 1,
            "email" => "test@test"
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }
}

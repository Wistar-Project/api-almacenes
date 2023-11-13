<?php

namespace Tests\Feature;

use App\Models\Administrador;
use App\Models\Alojamiento;
use App\Models\Conductor;
use App\Models\Funcionario;
use App\Models\Persona;
use App\Models\Sede;
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

    public function test_ver_informacion_de_paquete(){
        Paquete::create([
            "id" => 10,
            "peso_en_kg" => 5,
            "destino" => 1,
            "email" => "prueba@gmail.com"
        ]);
        $response = $this -> actingAs($this->crearAdministrador())->get('/api/v1/paquetes/10');
        $response -> assertStatus(200);
        $response -> assertJson([
            "id" => 10,
            "pesoEnKg" => 5,
            "direccionDestino" => "Dirección 1",
            "vehiculoAsignado" => "Ninguno",
            "email" => "prueba@gmail.com",
            "loteAsignado" => "Ninguno"
        ]);
    }

    public function test_ver_informacion_de_paquete_sin_autenticarse(){
        $response = $this ->get('/api/v1/paquetes/10', [
            "Accept" => "application/json"
        ]);
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_mostrar_paquetes_para_asignar(){
        Alojamiento::create([ "id" => 11, "direccion" => "Dirección 11"]);
        Sede::create([ "id" => 11 ]);
        Paquete::create([
            "id" => 11,
            "peso_en_kg" => 5,
            "destino" => 11,
            "email" => "a@gmail.com"
        ]);
        Paquete::create([
            "id" => 12,
            "peso_en_kg" => 5,
            "destino" => 11,
            "email" => "a@gmail.com"
        ]);
        Paquete::create([
            "id" => 13,
            "peso_en_kg" => 5,
            "destino" => 11,
            "email" => "a@gmail.com"
        ]);
        $response = $this -> actingAs($this->crearAdministrador())->get('/api/v1/paquetes/asignar/11');
        $response -> assertStatus(200);
        $response -> assertExactJson([
            11, 12, 13
        ]);
    }

    public function test_mostrar_paquetes_para_asignar_no_autenticado(){
        $response = $this ->get('/api/v1/paquetes/asignar/11', [
            "Accept" => "application/json"
        ]);
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_mostrar_paquetes_para_asignar_siendo_conductor(){
        $response = $this -> actingAs($this->crearChofer())->get('/api/v1/paquetes/asignar/11', [
            "Accept" => "application/json"
        ]);
        $response -> assertStatus(401);
        $response -> assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }
}

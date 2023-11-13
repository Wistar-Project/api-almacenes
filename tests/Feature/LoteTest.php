<?php

namespace Tests\Feature;

use App\Models\Alojamiento;
use App\Models\Conductor;
use App\Models\Funcionario;
use App\Models\Paquete;
use App\Models\Persona;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Lote;

class LoteTest extends TestCase
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

    public function test_listar_lotes()
    {
        $response = $this->actingAs($this -> crearFuncionario(), "api") -> get('/api/v1/lotes');
        $response->assertStatus(200);
        $response->assertExactJson(Lote::all() -> toArray());
    }

    public function test_listar_sin_autenticarse()
    {
        $response = $this-> get('/api/v1/lotes', [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_listar_siendo_chofer()
    {
        $response = $this->actingAs($this -> crearChofer(), "api")->get('/api/v1/lotes');
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_crear_lote()
    {
        Alojamiento::create([
            "id" => 1,
            "direccion" => "Dirección 1"
        ]);
        Sede::create([
            "id" => 1
        ]);
        $response = $this->actingAs($this -> crearFuncionario(), "api")->post('/api/v1/lotes', [
            "destino" => 1
        ]);
        $response->assertStatus(201);
        $this -> assertDatabaseHas('lotes', [
            "destino" => 1,
        ]);
    }

    public function test_crear_lote_sin_autenticarse(){
        Alojamiento::create([
            "id" => 2,
            "direccion" => "Dirección 2"
        ]);
        Sede::create([
            "id" => 2
        ]);
        $response = $this->post('/api/v1/lotes', [
            "destino" => 2
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_crear_lote_siendo_chofer(){
        Alojamiento::create([
            "id" => 3,
            "direccion" => "Dirección 3"
        ]);
        Sede::create([
            "id" => 3
        ]);
        $response = $this->actingAs($this -> crearChofer(), "api")->post('/api/v1/lotes', [
            "destino" => 3
        ]);
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_asignar_paquete_a_lote()
    {
        Paquete::create([
            "id" => 1,
            "destino" => 1,
            "email" => "a@gmail.com",
            "peso_en_kg" => 5
        ]);
        $response = $this->actingAs($this->crearFuncionario(), "api")->post('/api/v1/lotes/asignar', [
            "lote" => 1,
            "paquete" => 1
        ]);
        $response->assertStatus(201);
        $this -> assertDatabaseHas('lote_formado_por', [
            "id_lote" => 1,
            "id_paquete" => 1
        ]);
    }

    public function test_asignar_paquete_a_lote_sin_autenticarse()
    {
        $response = $this->post('/api/v1/lotes/asignar', [
            "lote" => 1,
            "paquete" => 1
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "Unauthenticated."
        ]);
    }

    public function test_asignar_paquete_a_lote_siendo_chofer()
    {
        $response = $this->actingAs($this->crearChofer(), "api")->post('/api/v1/lotes/asignar', [
            "lote" => 1,
            "paquete" => 1
        ], [
            "Accept" => "application/json"
        ]);
        $response->assertStatus(401);
        $response->assertExactJson([
            "message" => "No tienes permiso para ver esto."
        ]);
    }

    public function test_mostrar_lote()
    {
        $response = $this->actingAs($this->crearFuncionario(), "api")->get('/api/v1/lotes/1');
        $response->assertStatus(200);
        $response->assertJson([
            "pesoEnKg" => 5,
            "camionAsignado" => "Ninguno",
            "direccionDestino" => "Dirección 1",
            "cantidadPaquetes" => 1
        ]);
    }

}

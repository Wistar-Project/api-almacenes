<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Paquete;

class PaqueteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ListarPaquetes()
    {
        $response = $this->get('/api/v1/paquetes');
        $response->assertStatus(200);
        $response->assertExactJson(Paquete::all() -> toArray());
    }

    public function test_CrearPaquete()
    {
        $response = $this->post('/api/v1/paquetes', [
            "pesoEnKilogramos" => 3,
            "destino" => 1
        ]);
        $response->assertStatus(200);
        $this -> assertDatabaseHas('paquetes', [
            "destino" => 1,
            "peso_en_kg" => 3
        ]);
    }
}

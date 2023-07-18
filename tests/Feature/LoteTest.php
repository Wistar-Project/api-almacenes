<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Lote;

class LoteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ListarLotes()
    {
        $response = $this->get('/api/v1/lotes');
        $response->assertStatus(200);
        $response->assertExactJson(Lote::all() -> toArray());
    }

    public function test_CrearLote()
    {
        $response = $this->post('/api/v1/lotes', [
            "destino" => 1
        ]);
        $response->assertStatus(200);
        $this -> assertDatabaseHas('lotes', [
            "destino" => 1,
        ]);
    }

    public function test_AsignarPaqueteALote()
    {
        $response = $this->post('/api/v1/lotes/asignar', [
            "lote" => 1,
            "paquete" => 1
        ]);
        $response->assertStatus(200);
        $this -> assertDatabaseHas('lote_formado_por', [
            "id_lote" => 1,
            "id_paquete" => 1
        ]);
    }
}

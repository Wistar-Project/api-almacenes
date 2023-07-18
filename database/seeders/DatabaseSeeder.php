<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lote;
use App\Models\LoteFormadoPor;
use App\Models\Paquete;
use App\Models\Alojamiento;
use App\Models\SedeHogar;
use App\Models\Sede;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Alojamiento::create([
            "id" => 1,
            "direccion" => "Avenida brasil 1295"
        ]);
        SedeHogar::create([
            "id" => 1
        ]);
        Sede::create([
            "id" => 1
        ]);
        Paquete::create([
            "id" => 1,
            "peso_en_kg" => 5,
            "destino" => 1
        ]);
        Lote::create([
            "id" => 1,
            "destino" => 1
        ]);
    }
}

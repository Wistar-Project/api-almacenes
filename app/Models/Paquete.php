<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Paquete extends Model
{
    use HasFactory;

    protected $fillable = [
        'peso_en_kg',
        'destino',
        'email'
    ];

    public function pickup(): HasOne
    {
        return $this->hasOne(PaqueteAsignadoAPickup::class, "id_paquete");
    }

    public function lote(): HasOne
    {
        return $this->hasOne(LoteFormadoPor::class, "id_paquete");
    }
}

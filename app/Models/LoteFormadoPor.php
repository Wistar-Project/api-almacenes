<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteFormadoPor extends Model
{
    use HasFactory;

    protected $table = "lote_formado_por";

    protected $fillable = [
        'id_lote',
        'id_paquete'
    ];
}

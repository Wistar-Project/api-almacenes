<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sede extends Model
{
    use HasFactory;

    public function alojamiento(): HasOne
    {
        return $this->hasOne(Alojamiento::class, "id");
    }
}

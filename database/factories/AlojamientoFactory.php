<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AlojamientoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "direccion" => $this -> faker -> address()
        ];
    }
}

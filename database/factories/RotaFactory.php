<?php

namespace Database\Factories;

use App\Models\Rota;
use Illuminate\Database\Eloquent\Factories\Factory;

class RotaFactory extends Factory
{
    protected $model = Rota::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'week_commence_date' => now()->next(1),
            'shop_id' => 1
        ];
    }
}

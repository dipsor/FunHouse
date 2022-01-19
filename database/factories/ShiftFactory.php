<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    protected $model = Shift::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'rota_id' => 1,
            'staff_id' => 1,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(8),
        ];
    }
}

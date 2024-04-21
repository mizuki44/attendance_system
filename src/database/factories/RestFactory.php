<?php

namespace Database\Factories;

use App\Models\Rest;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'attendance_id'=>\App\Models\Attendance::factory(),
            'start_time' => $this->faker->Datetime(),
            'end_time'=> $this->faker->Datetime(),
            'created_at' => $this->faker->Datetime(),
        'updated_at' => $this->faker->Datetime(),
        ];
    }
}

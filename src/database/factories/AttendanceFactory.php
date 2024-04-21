<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_time' => $this->faker->Datetime(),
            'end_time'=> $this->faker->Datetime(),
            'date'=> $this->faker->Date(),
            'created_at' => $this->faker->Datetime(),
        'updated_at' => $this->faker->Datetime(),
        'user_id'=>\App\Models\User::factory(), 
        ];
    }
}

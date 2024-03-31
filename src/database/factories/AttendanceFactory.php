<?php

namespace Database\Factories;

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
            'id' => $this->faker->numberBetween(1,100),
            'start_time' => $this->faker->Datetime(),
            'end_time'=> $this->faker->Datetime(),
            'created_at' => $this->faker->Datetime(),
        'updated_at' => $this->faker->Datetime(),
        ];
    }
}

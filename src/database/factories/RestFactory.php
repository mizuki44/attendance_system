<?php

namespace Database\Factories;

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
        'user_id' => $this->faker->numberBetween(1,100),
        // ↑外部キー制約ありエラー出る。親データがないから？
            'start_time' => $this->faker->Datetime(),
            'end_time'=> $this->faker->Datetime(),
            'created_at' => $this->faker->Datetime(),
        'updated_at' => $this->faker->Datetime(),
        ];
    }
}

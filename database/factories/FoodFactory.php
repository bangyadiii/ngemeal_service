<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->sentence(3),
            "description" => $this->faker->paragraph(),
            "ingredients" => $this->faker->paragraph(),
            "price" => $this->faker->randomNumber(),
            "rate" => \rand(1, 5),
            "types" => $this->faker->sentence(1),
            "picture_path" => $this->faker->url(),
            "archived" => null,
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "store_name" => $this->faker->word(),
            "address" => $this->faker->address(),
            "description" => $this->faker->sentence(3),
            "rekening_number" => $this->faker->creditCardNumber(),
            "logo_path" => $this->faker->imageUrl(),
        ];
    }
}

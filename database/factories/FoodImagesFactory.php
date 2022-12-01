<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodImagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "image_path" => "tmp/food/6388280d329b6-male-avatar-profile-icon-of-smiling-caucasian-man-vector.jpg",
            "image_url" => "https://ngemeal-bucket.s3.ap-southeast-1.amazonaws.com/tmp/food/6388280d329b6-male-avatar-profile-icon-of-smiling-caucasian-man-vector.jpg",
        ];
    }
}

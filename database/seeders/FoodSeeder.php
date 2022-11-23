<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Food::factory(100)
            ->hasImages(3)
            ->state(new Sequence(
                fn ($sequence) => ["store_id" => Store::all()->random()]
            ))
            ->create();
    }
}

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
            ->hasImages(3, new Sequence(fn ($seq) => [
                "is_primary" => $seq->index % 3 == 0 ? true : false
            ]))
            ->state(new Sequence(
                fn ($sequence) => [
                    "store_id" => Store::all()->random(),
                ]
            ))
            ->create();
    }
}

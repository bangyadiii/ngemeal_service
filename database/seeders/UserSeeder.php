<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => \bcrypt("password"),
        ]);
        $customerRole = Role::where("slug", "customer")->first();
        $sellerRole = Role::where("slug", "seller")->first();

        $user->roles()->attach(Role::where("slug", "admin")->first());
        $user->roles()->attach([$customerRole->id]);

        User::factory(10)->hasStore()
            ->hasAttached($sellerRole)->create();

        User::factory(100)
            ->hasAttached($customerRole)->create();
    }
}

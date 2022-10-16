<?php

namespace Database\Seeders;

use App\Models\Role;
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

        $user->roles()->attach(Role::where("slug", "admin")->first());
        $user->roles()->attach(Role::where("slug", "customer")->first());
    }
}

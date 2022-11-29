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
        $superAdm = Role::where("slug", "super")->first();

        $user->roles()->associate($superAdm)->save();

        User::factory(10)
            ->seller()
            ->hasStore()
            ->create();

        User::factory(100)
            ->create();
    }
}

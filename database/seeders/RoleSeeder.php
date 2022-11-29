<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super'],
            ['name' => 'Administrator', 'slug' => 'admin'],
            ['name' => 'Customer', 'slug' => 'customer'],
            ['name' => 'Seller', 'slug' => 'seller'],
        ];

        collect($roles)->each(function ($role) {
            Role::create($role);
        });
    }
}

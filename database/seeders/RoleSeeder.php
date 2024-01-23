<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //ejecutamos el seeder con el comando php artisan db:seed --class=RoleSeeder
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
            ]);
    }
}

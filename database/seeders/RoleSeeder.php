<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'author',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'subscriber',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

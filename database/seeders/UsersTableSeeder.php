<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('USERS')->insert([
            [
                'name' => 'Dr. Christian Mendoza',
                'email' => 'c.mendoza@lalysdent.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dra. Claudia Ibáñez',
                'email' => 'c.ibanez@lalysdent.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dr. Alan Nicolas Villarroel',
                'email' => 'a.villarroel@lalysdent.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dra. Laly Torrico',
                'email' => 'l.torrico@lalysdent.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dr. Sergio Escalante',
                'email' => 's.escalante@lalysdent.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
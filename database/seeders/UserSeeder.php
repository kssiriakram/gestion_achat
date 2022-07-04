<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email'=> 'kssiriakram@gmail.com',
            'username' => 'kssiriakram',
            'password' => Hash::make('kssiriakram'),
            'type' => 'emetteur',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'mohamedakram.kssiri@etu.uae.ac.ma',
            'username' => 'mohamedakram.kssiri',
            'password' => Hash::make('mohamedakram.kssiri'),
            'type' => 'chef de service',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'test@gmail.com',
            'username' => 'test',
            'password' => Hash::make('test'),
            'type' => 'acheteur',
            'departement' => 'IT'
        ]);
    }
}

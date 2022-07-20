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
            'type' => 'directeur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'mohamedakram.kssiri@etu.uae.ac.ma',
            'username' => 'mohamedakram.kssiri',
            'password' => Hash::make('mohamedakram.kssiri'),
            'type' => 'manager',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'test@gmail.com',
            'username' => 'test',
            'password' => Hash::make('test'),
            'type' => 'emetteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'ayakssiri@gmail.com',
            'username' => 'ayakssiri',
            'password' => Hash::make('ayakssiri'),
            'type' => 'acheteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'departement' => 'IT'
        ]);

       /* DB::table('users')->insert([
            'email'=> 'abdelaziz.bekraoui@coficab.com',
            'username' => 'abdelaziz.bekraoui',
            'password' => Hash::make('abdelaziz.bekraoui'),
            'type' => 'emetteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'yacine.elyounoussi@coficab.com',
            'username' => 'yacine.elyounoussi',
            'password' => Hash::make('yacine.elyounoussi'),
            'type' => 'emetteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'reda.alaoui@coficab.com',
            'username' => 'reda.alaoui',
            'password' => Hash::make('reda.alaoui'),
            'type' => 'emetteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'departement' => 'IT'
        ]);



        DB::table('users')->insert([
            'email'=> 'monhem.amrani@coficab.com',
            'username' => 'monhem.amrani',
            'password' => Hash::make('monhem.amrani'),
            'type' => 'manager',
            'superieur' => 'monhem.amrani',
            'societe' => 'COFMA',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'iabdouene.youssef@coficab.com',
            'username' => 'iabdouene.youssef',
            'password' => Hash::make('iabdouene.youssef'),
            'type' => 'directeur',
            'superieur' => 'monhem.amrani',
            'societe' => 'COFMA',
            'departement' => 'IT'
        ]);*/
    }
}

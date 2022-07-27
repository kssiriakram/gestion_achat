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
      /* DB::table('users')->insert([
            'matricule' => '1000000000',
            'email'=> 'kssiriakram@gmail.com',
            'username' => 'kssiriakram',
            'password' => Hash::make('kssiriakram'),
            'type' => 'directeur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'email_suprv' => 'monhem.amrani@coficab.com',
            'departement' => 'IT'

        ]);

        DB::table('users')->insert([
            'matricule' => '1100000000',
            'email'=> 'mohamedakram.kssiri@etu.uae.ac.ma',
            'username' => 'mohamedakram.kssiri',
            'password' => Hash::make('mohamedakram.kssiri'),
            'type' => 'manager',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'email_suprv' => 'monhem.amrani@coficab.com',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'matricule' => '1110000000',
            'email'=> 'test@gmail.com',
            'username' => 'test',
            'password' => Hash::make('test'),
            'type' => 'emetteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'email_suprv' => 'monhem.amrani@coficab.com',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'matricule' => '1111000000',
            'email'=> 'ayakssiri@gmail.com',
            'username' => 'ayakssiri',
            'password' => Hash::make('ayakssiri'),
            'type' => 'acheteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'email_suprv' => 'monhem.amrani@coficab.com',
            'departement' => 'IT'
        ]);*/

       DB::table('users')->insert([
            'email'=> 'abdelaziz.bekraoui@coficab.com',
            'username' => 'abdelaziz.bekraoui',
            'password' => Hash::make('abdelaziz.bekraoui'),
            'type' => 'emetteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'email_suprv' => 'monhem.amrani@coficab.com',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'yacine.elyounoussi@coficab.com',
            'username' => 'yacine.elyounoussi',
            'password' => Hash::make('yacine.elyounoussi'),
            'type' => 'emetteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'email_suprv' => 'monhem.amrani@coficab.com',
            'departement' => 'IT'
        ]);

        DB::table('users')->insert([
            'email'=> 'reda.alaoui@coficab.com',
            'username' => 'reda.alaoui',
            'password' => Hash::make('reda.alaoui'),
            'type' => 'emetteur',
            'societe' => 'COFMA',
            'superieur' => 'monhem.amrani',
            'email_suprv' => 'monhem.amrani@coficab.com',
            'departement' => 'IT'
        ]);









////////////////////////////directeurs/////////////////////////////////////////////////////
        DB::table('users')->insert([
            'email'=> 'nabil.salem@coficab.com',
            'username' => 'nabil.salem',
            'password' => Hash::make('nabil.salem'),
            'type' => 'directeur',
            'societe' => 'COFMA',
        ]);

        DB::table('users')->insert([
            'email'=> 'Issam.Jartila@coficab.com',
            'username' => 'Issam.Jartila',
            'password' => Hash::make('Issam.Jartila'),
            'type' => 'directeur',
            'societe' => 'COFMA',
        ]);

        DB::table('users')->insert([
            'email'=> 'Mohamed.kassaagi@coficab.com',
            'username' => 'Mohamed.kassaagi',
            'password' => Hash::make('Mohamed.kassaagi'),
            'type' => 'directeur',
            'societe' => 'COFMA',
        ]);

        DB::table('users')->insert([
            'email'=> 'Ghaieth.Merdassi@coficab.com',
            'username' => 'Ghaieth.Merdassi',
            'password' => Hash::make('Ghaieth.Merdassi'),
            'type' => 'directeur',
            'societe' => 'COFMA',
        ]);

//////////////////////////////////Acheteurs////////////////////////////////////////


DB::table('users')->insert([
    'email'=> 'darhour.aicha@coficab.com',
    'username' => 'darhour.aicha',
    'password' => Hash::make('darhour.aicha'),
    'type' => 'acheteur',
    'societe' => 'COFMA',
]);

DB::table('users')->insert([
    'email'=> 'iabdouene.youssef@coficab.com',
    'username' => 'iabdouene.youssef',
    'password' => Hash::make('iabdouene.youssef'),
    'type' => 'acheteur',
    'societe' => 'COFMA',
]);

DB::table('users')->insert([
    'email'=> 'karima.zeraidi@coficab.com',
    'username' => 'karima.zeraidi',
    'password' => Hash::make('karima.zeraidi'),
    'type' => 'acheteur',
    'societe' => 'COFMA',
]);

DB::table('users')->insert([
    'email'=> 'basma.eddaoudi@coficab.com',
    'username' => 'basma.eddaoudi',
    'password' => Hash::make('basma.eddaoudi'),
    'type' => 'acheteur',
    'societe' => 'COFMA',
]);

/////////////////////////////////////Managers/////////////////////////////////
DB::table('users')->insert([
    'email'=> 'monhem.amrani@coficab.com',
    'username' => 'monhem.amrani',
    'password' => Hash::make('monhem.amrani'),
    'type' => 'manager',
    'societe' => 'COFMA',
    'departement' => 'IT'
]);

DB::table('users')->insert([
    'email'=> 'Rachid.Menkali@coficab.com',
    'username' => 'Rachid.Menkali',
    'password' => Hash::make('Rachid.Menkali'),
    'type' => 'manager',
    'societe' => 'COFMA',
    'departement' => 'RH'
]);

DB::table('users')->insert([
    'email'=> 'Khalid.ElHadad@coficab.com',
    'username' => 'Khalid.ElHadad',
    'password' => Hash::make('Khalid.ElHadad'),
    'type' => 'manager',
    'societe' => 'COFMA',
    'departement' => 'HSE'
]);

DB::table('users')->insert([
    'email'=> 'Hanane.ElHajry@coficab.com',
    'username' => 'Hanane.ElHajry',
    'password' => Hash::make('Hanane.ElHajry'),
    'type' => 'manager',
    'societe' => 'COFMA',
    'departement' => 'Qualité'
]);

DB::table('users')->insert([
    'email'=> 'Halima.Elmrabet@coficab.com',
    'username' => 'Halima.Elmrabet',
    'password' => Hash::make('Halima.Elmrabet'),
    'type' => 'manager',
    'societe' => 'COFMA',
    'departement' => 'IP'
]);

DB::table('users')->insert([
    'email'=> 'Abdelaziz.Hassouni@coficab.com',
    'username' => 'Abdelaziz.Hassouni',
    'password' => Hash::make('Abdelaziz.Hassouni'),
    'type' => 'manager',
    'societe' => 'COFMA',
    'departement' => 'Metal'
]);















    }
}

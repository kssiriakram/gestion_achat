<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => 'monhem.amrani',
            'password' => 'akram1234',
            'email'=> 'monhem.amrani@coficab.com',
            'Company' => 'COFMA',
            'Country' => 'MA',
            'Title' => 'IT Technician',//Hash::make('Badir'),
            'Departement' => 'Information Systems',
            'type' => 'chef de service'
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}

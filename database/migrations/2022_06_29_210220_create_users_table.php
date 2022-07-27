<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('username')->unique();
            $table->enum('type', ['emetteur', 'acheteur','manager','directeur']);
            $table->string('email')->unique();
            $table->string('departement');
            $table->string('password');
            $table->string('societe');
            $table->string('superieur');
            $table->string('email_suprv');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigneDaFournisseursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ligne_da_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->double('prix')->default(0);
            $table->enum('devise', ['EUR','DH']);
            $table->double('remise')->default(0);


            $table->unsignedBigInteger("id_ligne_da");
            $table->foreign('id_ligne_da')->references('id')->on('ligne_das');

            $table->unsignedBigInteger("id_fournisseur");
            $table->foreign('id_fournisseur')->references('id')->on('fournisseurs');



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
        Schema::dropIfExists('ligne_da_fournisseurs');
    }
}

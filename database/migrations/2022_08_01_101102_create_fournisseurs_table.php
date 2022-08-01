<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFournisseursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom_fournisseur')->unique();
            $table->double('prix')->default(0);
            $table->enum('devise', ['EUR','DH']);
            $table->double('remise')->default(0);
            $table->double('prix_total')->default(0);

            $table->unsignedBigInteger("id_tab_comparatif");
            $table->foreign('id_tab_comparatif')->references('id')->on('tab_comparatifs');




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
        Schema::dropIfExists('fournisseurs');
    }
}

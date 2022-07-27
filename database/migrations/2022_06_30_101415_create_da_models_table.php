<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('da_models', function (Blueprint $table) {
            $table->id();
            $table->datetime("date_emetteur")->nullable()->default(NULL);
            $table->datetime("date_chef_service")->nullable()->default(NULL);
            $table->datetime("date_directeur")->nullable()->default(NULL);
            $table->datetime("date_acheteur")->nullable()->default(NULL);

            $table->string("fournisseur")->nullable()->default(NULL);

            $table->text('commentaire_manager')->nullable()->default(NULL);
            $table->text('commentaire_directeur')->nullable()->default(NULL);
            $table->text('commentaire_acheteur')->nullable()->default(NULL);
 
          

            $table->unsignedBigInteger("id_emetteur");
            $table->unsignedBigInteger("id_acheteur");
            $table->foreign('id_emetteur')->references('id')->on('users');
            $table->foreign('id_acheteur')->references('id')->on('users');
            $table->date("delai")->nullable()->default(NULL);


            $table->boolean('validation_manager')->nullable()->default(false);
            $table->boolean('validation_directeur')->nullable()->default(false);
            $table->boolean('validation_acheteur')->nullable()->default(false);



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
        Schema::dropIfExists('da_models');
    }
}

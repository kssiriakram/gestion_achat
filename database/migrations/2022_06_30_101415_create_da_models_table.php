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
            $table->date("date_emetteur")->nullable();
            $table->date("date_chef_service")->nullable()->default(NULL);
            $table->date("date_directeur")->nullable()->default(NULL);
            $table->text('commentaire')->nullable();
            $table->string("reference");
            $table->integer("qte");
            $table->unsignedBigInteger("id_emetteur");
            $table->unsignedBigInteger("id_acheteur");
            $table->foreign('id_emetteur')->references('id')->on('users');
            $table->foreign('id_acheteur')->references('id')->on('users');
            $table->date("delai");
            $table->integer("code_CC")->nullable();
            $table->integer("code_NE")->nullable();
            $table->boolean('validation')->nullable()->default(false);
            $table->enum('societe', ['coficab maroc', 'coficab international']);
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

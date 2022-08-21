<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabComparatifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tab_comparatifs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();



            $table->text('commentaire_acheteur')->nullable()->default(NULL);
            $table->text('commentaire_manager')->nullable()->default(NULL);
            $table->text('commentaire_directeur')->nullable()->default(NULL);

            $table->datetime("date_chef_service")->nullable()->default(NULL);
            $table->datetime("date_directeur")->nullable()->default(NULL);

            $table->boolean('validation_manager')->nullable()->default(false);
            $table->boolean('validation_directeur')->nullable()->default(false);
           



            $table->foreign('id')->references('id')->on('da_models');

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
        Schema::dropIfExists('tab_comparatifs');
    }
}

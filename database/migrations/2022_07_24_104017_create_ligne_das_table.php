<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigneDasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ligne_das', function (Blueprint $table) {
            $table->id();
            $table->string("reference");
            $table->integer("qte");
            $table->string("code_CC")->nullable()->default(NULL);
            $table->string("code_NE")->nullable();
            $table->string("file")->nullable()->default(NULL);
            $table->string("designation")->nullable()->default(NULL);
            $table->unsignedBigInteger("id_da");
            $table->foreign('id_da')->references('id')->on('da_models');
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
        Schema::dropIfExists('ligne_das');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Createtbcodigos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbcodigos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('empresa_id')->unsigned()->nullable();
            $table->integer('encargado_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->longText('codigo');
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
        Schema::dropIfExists('tbcodigos');
    }
}

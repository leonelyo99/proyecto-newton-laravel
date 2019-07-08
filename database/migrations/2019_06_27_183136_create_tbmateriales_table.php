<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbmaterialesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbmateriales', function (Blueprint $table) {
            $table->bigIncrements('id')->unique()->autoIncrement();
            $table->integer('pedido_id')->unsigned()->nullable();
            $table->string('material');
            $table->integer('cantidad');
            $table->smallInteger('urgencia');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbmateriales');
    }
}

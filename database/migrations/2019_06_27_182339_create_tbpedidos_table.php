<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbpedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbpedidos', function (Blueprint $table) {
            $table->bigIncrements('id')->unique()->autoIncrement();
            $table->integer('user_id')->unsigned();
            $table->integer('encargado_id')->unsigned()->nullable();
            $table->integer('empresa_id')->unsigned()->nullable();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->smallInteger('progreso');
            $table->integer('precio');
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
        Schema::dropIfExists('tbpedidos');
    }
}

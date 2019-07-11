<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbempresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbempresas', function (Blueprint $table) {
            $table->bigIncrements('id')->unique()->autoIncrement();
            $table->string('nombre',40);
            $table->string('apellido',40);
            $table->bigInteger('documento')->unsigned()->unique();
            $table->string('nombreEmpresa');
            $table->string('password',50);
            $table->string('img')->nullable();
            $table->string('ubicacion',50);
            $table->string('provincia',100);
            $table->string('pais',100);
            $table->enum('role', ['usuario', 'encargado', 'empresa']);
            $table->enum('estado', ['true', 'false']);
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
        Schema::dropIfExists('tbempresas');
    }
}

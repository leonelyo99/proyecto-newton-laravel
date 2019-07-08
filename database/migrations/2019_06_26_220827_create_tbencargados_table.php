<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbencargadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbencargados', function (Blueprint $table) {
            $table->bigIncrements('id')->unique()->autoIncrement();
            $table->integer('empresa_id')->unsigned();
            $table->string('nombre',40);
            $table->string('apellido',40);
            $table->string('usuario',40)->unique();
            $table->string('password',50);
            $table->string('img')->nullable();
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
        Schema::dropIfExists('tbencargados');
    }
}

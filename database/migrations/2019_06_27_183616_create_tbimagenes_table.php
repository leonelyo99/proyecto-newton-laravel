<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbimagenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbimagenes', function (Blueprint $table) {
            $table->bigIncrements('id')->unique()->autoIncrement();
            $table->integer('pedido_id')->unsigned();
            $table->string('imagen');
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
        Schema::dropIfExists('tbimagenes');
    }
}

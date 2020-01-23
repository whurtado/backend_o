<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblarticulos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fvcnombre', 50);
            $table->string('fvccodigo_barras', 100);
            $table->string('fvcdescripcion', 150)->nullable();
            $table->float('flngvalor');
            $table->integer('cantidad');
            $table->string('flvrequieredeposito',2);
            $table->float('flngvalorDeposito');

            $table->text('fvcimagen');
            $table->unsignedInteger('fvccategoria_id');
            $table->unsignedInteger('fvcusuario_id');
            $table->foreign('fvccategoria_id')->references('id')->on('tblcategorias');
            $table->foreign('fvcusuario_id')->references('id')->on('users');

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
        Schema::dropIfExists('articulos');
    }
}

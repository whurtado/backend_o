<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblcategorias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fvcnombre', 50);
            $table->string('fvcgenero', 10);
            $table->string('fvcdescripcion', 150)->nullable();
            $table->unsignedBigInteger('fvcusuario_id');
            $table->integer('fvcsede_creacion');
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
        Schema::dropIfExists('categorias');
    }
}

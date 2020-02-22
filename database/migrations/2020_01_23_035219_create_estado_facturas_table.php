<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadoFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblestadofactura', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fvcfactura_id');
            $table->date('fdtfecha');
            $table->string('fvcestado', 2);
            $table->string('fvcdescripcion', 250);
            $table->integer('fvcsede_creacion');
            $table->unsignedBigInteger('fvcusuario_id');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
            //$table->foreign('fvcfactura_id')->references('id')->on('tblfactura');

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
        Schema::dropIfExists('tblestadofactura');
    }
}

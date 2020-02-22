<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEstadoFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblestadodetallefactura', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('fvcfactura_id');
            $table->date('fdtfecha');
            $table->text('fvcnota');
            $table->string('fvcestado', 20);
            $table->unsignedBigInteger('fvcusuario_id');
            $table->integer('fvcsede_creacion');
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
        Schema::dropIfExists('tblestadodetallefactura');
    }
}

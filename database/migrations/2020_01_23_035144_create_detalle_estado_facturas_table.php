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
            $table->unsignedInteger('fvcfactura_id');
            $table->date('fdtfecha');
            $table->text('fvcnota');
            $table->string('fvcestado', 20);
            $table->unsignedInteger('fvcusuario_id');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
            $table->foreign('fvcfactura_id')->references('id')->on('tblfactura');
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

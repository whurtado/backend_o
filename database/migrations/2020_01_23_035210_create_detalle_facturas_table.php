<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbldetallefactura', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('fvcfactura_id');
            $table->unsignedBigInteger('fvcarticulo_id');
            $table->string('fvctalla',10);
            $table->string('fvcdescripcion', 250);
            $table->timestamp('fvcentregado');
            $table->string('fvcmedicion', 100);
            $table->string('fvcestadoprenda', 20);
            $table->text('fvcnota');
            $table->string('fvcestado', 2);
            $table->unsignedBigInteger('fvcusuario_id');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
            //$table->foreign('fvcfactura_id')->references('id')->on('tblfactura');
            //$table->foreign('fvcarticulo_id')->references('id')->on('tblarticulos');

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
        Schema::dropIfExists('tbldetallefactura');
    }
}

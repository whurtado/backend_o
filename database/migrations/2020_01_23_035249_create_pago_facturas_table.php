<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagoFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpagofactura', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fvcfactura_id');
            $table->float('flngvalor',10);
            $table->date('fdtfecha');
            $table->string('fvcformapago',50);
            $table->string('fvcnotarjeta', 100);
            $table->string('fvcdescripciontarjeta', 250);
            //$table->integer('fvccodigo');
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
        Schema::dropIfExists('tblpagofactura');
    }
}

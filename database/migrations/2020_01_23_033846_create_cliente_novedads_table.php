<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClienteNovedadsTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblclientenovedad', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fdtdescripcion', 150)->nullable();
            $table->date('fdtfecha');
            $table->unsignedBigInteger('fvccliente_id');
            $table->unsignedBigInteger('fvcusuario_id');
            //$table->foreign('fvccliente_id')->references('id')->on('tblcliente');
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
        Schema::dropIfExists('tblclientenovedad');
    }
}

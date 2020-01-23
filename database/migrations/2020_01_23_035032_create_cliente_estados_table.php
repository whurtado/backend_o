<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClienteEstado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblclienteestado', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fvccliente_id');
            $table->date('fdtfecha', 150)->nullable();
            $table->string('fdtobservacion', 50);
            $table->string('fdtestado');
            $table->unsignedInteger('fvcusuario_id');
            $table->foreign('fvccliente_id')->references('id')->on('tblcliente');
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
        Schema::dropIfExists('tblclienteestado');
    }
}

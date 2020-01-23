<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleClienteReferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbldetalleclientereferencias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dfvid');
            $table->string('dfvnombre_referencia', 50);
            $table->string('dfvtelefono_referencia', 25);
            $table->unsignedInteger('fvccliente_id')->unsigned();
            $table->unsignedInteger('fvcusuario_id');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
            $table->foreign('fvccliente_id')->references('id')->on('tblcliente');



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
        Schema::dropIfExists('tbldetalleclientereferencias');
    }
}

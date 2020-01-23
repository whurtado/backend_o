<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblregistropago', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fvcfactura', 100);
            $table->float('flngvalorFactura',10);
            $table->date('fvcfechaPagoFactura');
            $table->float('flngvalorDeduccion',10);
            $table->float('flngvalorPagar',10);
            $table->text('fvcobservacion');
            $table->string('fvcestado', 50);
            $table->unsignedInteger('fvcusuario_id');
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
        Schema::dropIfExists('tblregistropago');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpago', function (Blueprint $table) {

            $table->increments('id');
            $table->string('fvcnombre', 200);
            $table->string('fvcdocumento', 30);
            $table->string('fvctelefono', 20);
            $table->text('fvcdireccion');
            $table->float('flngvalor');
            $table->text('fvcobservacion');
            $table->string('fvcahh', 30);
            $table->string('fvcfactura', 30);
            $table->date('fdtfecha');
            $table->integer('fintfactura');
            $table->unsignedBigInteger('fvcusuario_id');
            $table->unsignedBigInteger('fvcclasificacionpago_id');
            $table->unsignedBigInteger('fvcpagofactura_id');
            $table->unsignedBigInteger('fvcautorizacion_id');
            $table->unsignedBigInteger('fvcsede_id');
            $table->integer('fvcsede_creacion');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
            //$table->foreign('fvcpagofactura_id')->references('id')->on('tblpagofactura');
            //$table->foreign('fvcautorizacion_id')->references('id')->on('tblautorizacion');
            //$table->foreign('fvcsede_id')->references('id')->on('tblsede');
            //$table->foreign('fvcclasificacionpago_id')->references('id')->on('tblclasificacionpago');

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
        Schema::dropIfExists('tblpago');
    }
}

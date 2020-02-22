<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblfactura', function (Blueprint $table) {
            $table->increments('id');

            $table->string('fvcnombre', 100);
            $table->string('fvcgenero', 10);
            $table->string('fvcsede', 20);
            $table->float('flngvalor',10);
            $table->float('flngabono',10);
            $table->date('fdtfechaentrega');
            $table->date('fdtfecharetorno');
            $table->string('fvcobservacion', 250);
            $table->date('fdtfecha');
            $table->string('fvcestado', 10);
            $table->string('fvctraje', 100);
            $table->date('fdtfechaprueba');
            $table->integer('fvccodigo');
            $table->string('fvcconfesion', 5);
            $table->string('fvcformapago', 50);
            $table->string('fvcnotarjeta', 100);
            $table->string('fvcdescripciontarjeta', 100);
            $table->string('fvcficha', 4);
            $table->string('fvcbloqueo', 2);
            $table->text('fvcmotivobloqueo');
            $table->float('flngdeposito');
            $table->unsignedBigInteger('fvcpagodeposito_id');
            $table->unsignedBigInteger('fvccliente_id');
            $table->unsignedBigInteger('fvcusuario_id');
            $table->unsignedBigInteger('fvcvendedor_id');
            $table->unsignedBigInteger('fvcsede_id');
            $table->integer('fvcsede_creacion');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
            /*$table->foreign('fvccliente_id')->references('id')->on('tblcliente');
            $table->foreign('fvcvendedor_id')->references('id')->on('tblvendedor');
            $table->foreign('fvcsede_id')->references('id')->on('tblsede');*/
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
        Schema::dropIfExists('tblfactura');
    }
}

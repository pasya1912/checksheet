<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_checkarea', function (Blueprint $table) {
            $table->integer('id',11)->autoIncrement();
            $table->integer('id_checksheet',11)->autoIncrement(false)->default('1');
            //column nama type text
            $table->text('nama')->nullable();
            //column deskrpsi type tinytext
            $table->text('deskripsi')->nullable();
            //float min max
            $table->float('min')->nullable();
            $table->float('max')->nullable();
            //column tipe enum
            $table->enum('tipe',['1','2','3'])->default('1');
            $table->foreign('id_checksheet')->references('id')->on('tm_checksheet');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_checkarea');
    }
};

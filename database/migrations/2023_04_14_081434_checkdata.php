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
        Schema::create('tt_checkdata', function (Blueprint $table) {
            $table->integer('id',11)->primary();
            $table->integer('id_checkarea',11)->unsigned();
            //column nama type text
            $table->string('nama',50)->default('m1');
            //column deskrpsi type tinytext
            $table->enum('barang',['first','last']);
            //datetime tanggal default current_timestamp
            $table->dateTime('tanggal')->default(DB::raw('current_timestamp()'));


            $table->string('user',16);
            $table->string('value',50);
            $table->enum('approval',['approved','wait','rejected'])->default('wait');
            $table->enum('mark',['0','1'])->default('0');
            $table->enum('shift',['1','2','3','1-long','3-long'])->default('1');
            $table->foreign('id_checkarea')->references('id')->on('tm_checkarea');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tt_checkdata');
    }
};

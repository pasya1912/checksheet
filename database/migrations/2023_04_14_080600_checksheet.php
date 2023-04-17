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
        Schema::create('tm_checksheet', function (Blueprint $table) {
            $table->integer('id',11)->primary();
            $table->string('line',50);
            $table->string('code',11);
            $table->string('nama',110)->nullable();
            $table->enum('jenis',['OIL PAN','TCC']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_checksheet');
    }
};

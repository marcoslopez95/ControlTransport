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
        Schema::dropIfExists('reception_amounts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('reception_amounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_detail_id');
            $table->foreignId('coin_id');
            $table->float('quantity');
            $table->float('neto');
            $table->integer('received');
            $table->timestamps();
        });
    }
};

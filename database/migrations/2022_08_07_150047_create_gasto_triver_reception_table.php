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
        Schema::create('gasto_triver_reception', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('gasto_id');
            $table->foreign('gasto_id')
                    ->references('id')
                    ->on('gastos')
                    ->restrictOnDelete()
                    ->cascadeOnUpdate();
            $table->foreignId('triver_reception_id');
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
        Schema::dropIfExists('gasto_triver_reception');
    }
};

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
        Schema::create('triver_receptions_table', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id');
            $table->string('route');
            $table->date('date_out');
            $table->date('date_in');
            $table->float('total');
            $table->float('pendiente');
            $table->foreignId('coin_id');
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
        Schema::dropIfExists('triver_reception_t_able');
    }
};

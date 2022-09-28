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
        Schema::create('driver_travel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id');
            $table->bigInteger('travel_id');
            $table->foreign('travel_id')
                ->on('travel')
                ->references('id')
                ->cascadeOnUpdate()
                ->onDelete('set null')
                ;
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
        Schema::dropIfExists('driver_travel');
    }
};

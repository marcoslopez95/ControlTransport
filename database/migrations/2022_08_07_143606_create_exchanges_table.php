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
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('to_coin');
            $table->foreign('to_coin')
                    ->on('coins')
                    ->references('id')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
            $table->bigInteger('from_coin');
            $table->foreign('from_coin')
                    ->on('coins')
                    ->references('id')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
            $table->float('change');
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
        Schema::dropIfExists('exchange');
    }
};

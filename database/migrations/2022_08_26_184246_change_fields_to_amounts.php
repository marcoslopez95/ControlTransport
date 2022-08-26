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
        Schema::table('amounts', function (Blueprint $table) {
            $table->dropColumn([
                'received',
                'neto'
            ]);
        });
        Schema::table('amounts', function (Blueprint $table) {
            $table->float('received')->nullable();
            $table->float('neto')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amounts', function (Blueprint $table) {
            //
        });
    }
};

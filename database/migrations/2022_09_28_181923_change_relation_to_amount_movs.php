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
        Schema::table('amount_movs', function (Blueprint $table) {
            $table->dropColumn('account_mov');
            $table->foreignId('account_mov_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amount_movs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('account_mov_id');
            $table->bigInteger('account_mov');
        });
    }
};

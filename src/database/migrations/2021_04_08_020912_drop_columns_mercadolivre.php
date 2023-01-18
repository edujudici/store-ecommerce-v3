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
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_url');
        });
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_search_type');
        });
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_seller_id');
        });
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_token');
        });
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_refresh_token');
        });
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_client_id');
        });
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_client_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mercadolivre', function (Blueprint $table) {
            //
        });
    }
};

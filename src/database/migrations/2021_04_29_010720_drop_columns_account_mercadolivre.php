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
            $table->dropColumn('mel_link_authorization');
        });
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_account_id');
        });
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_secret_key');
        });
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_redirect_url');
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

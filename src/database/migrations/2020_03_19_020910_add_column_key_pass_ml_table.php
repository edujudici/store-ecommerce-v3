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
            $table->string('mel_client_id')->nullable();
            $table->string('mel_client_secret')->nullable();
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
            $table->dropColumn('mel_client_id');
            $table->dropColumn('mel_client_secret');
        });
    }
};

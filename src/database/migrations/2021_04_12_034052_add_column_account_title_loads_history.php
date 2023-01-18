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
        Schema::table('loads_history', function (Blueprint $table) {
            $table->string('loh_account_title')->nullable()->after('loh_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loads_history', function (Blueprint $table) {
            $table->dropColumn('loh_account_title');
        });
    }
};

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
        Schema::table('loads_questions_history', function (Blueprint $table) {
            $table->bigInteger('lqh_account_id')->unsigned()->nullable()
                ->after('lqh_total_sync');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loads_questions_history', function (Blueprint $table) {
            $table->dropColumn('lqh_account_id');
        });
    }
};

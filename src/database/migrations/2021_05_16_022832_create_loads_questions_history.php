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
        Schema::create('loads_questions_history', function (Blueprint $table) {
            $table->bigIncrements('lqh_id');
            $table->bigInteger('lqh_total')->unsigned();
            $table->bigInteger('lqh_total_sync')->unsigned()->nullable();
            $table->string('lqh_account_title')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loads_questions_history');
    }
};

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
        # two operations because sqlite
        Schema::table('contacts', function (Blueprint $table) {
            $table->renameColumn('con_title', 'con_subject');
        });
        Schema::table('contacts', function (Blueprint $table) {
            $table->renameColumn('con_description', 'con_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            // code
        });
    }
};

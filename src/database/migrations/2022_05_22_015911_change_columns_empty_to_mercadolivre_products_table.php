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
        Schema::table('mercadolivre_products', function (Blueprint $table) {
            $table->string('mep_title')->nullable()->change();
            $table->float('mep_price')->nullable()->change();
            $table->string('mep_permalink')->nullable()->change();
            $table->string('mep_secure_thumbnail')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mercadolivre_products', function (Blueprint $table) {
            $table->string('mep_title')->change();
            $table->float('mep_price')->change();
            $table->string('mep_permalink')->change();
            $table->string('mep_secure_thumbnail')->change();
        });
    }
};

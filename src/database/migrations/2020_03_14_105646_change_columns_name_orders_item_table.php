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
        // two operations because sqlite
        Schema::table('orders_item', function (Blueprint $table) {
            $table->renameColumn('pro_id', 'ori_pro_id');
        });
        Schema::table('orders_item', function (Blueprint $table) {
            $table->renameColumn('pro_sku', 'ori_pro_sku');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders_item', function (Blueprint $table) {
            // code
        });
    }
};

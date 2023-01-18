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
        Schema::table('products', function (Blueprint $table) {
            $table->string('pro_sku')->nullable();
            $table->bigInteger('pro_seller_id')->nullable();
            $table->string('pro_category_id')->nullable();
            $table->string('pro_condition')->nullable();
            $table->text('pro_permalink')->nullable();
            $table->text('pro_thumbnail')->nullable();
            $table->text('pro_secure_thumbnail')->nullable();
            $table->text('pro_accepts_merc_pago')->nullable();
            $table->timestamp('pro_load_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('pro_sku');
            $table->dropColumn('pro_seller_id');
            $table->dropColumn('pro_category_id');
            $table->dropColumn('pro_condition');
            $table->dropColumn('pro_permalink');
            $table->dropColumn('pro_thumbnail');
            $table->dropColumn('pro_secure_thumbnail');
            $table->dropColumn('pro_accepts_merc_pago');
            $table->dropColumn('pro_load_date');
        });
    }
};

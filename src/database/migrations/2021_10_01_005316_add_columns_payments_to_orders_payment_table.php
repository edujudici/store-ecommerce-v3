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
        Schema::table('orders_payment', function (Blueprint $table) {
            $table->text('orp_resource_url')->nullable();
            $table->float('orp_total_paid_amount')->nullable();
            $table->float('orp_shipping_amount')->nullable();
            $table->timestamp('orp_date_approved')->nullable();
            $table->timestamp('orp_date_created')->nullable();
            $table->timestamp('orp_date_of_expiration')->nullable();
            $table->boolean('orp_live_mode')->nullable()
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders_payment', function (Blueprint $table) {
            $table->dropColumn('orp_resource_url');
            $table->dropColumn('orp_total_paid_amount');
            $table->dropColumn('orp_shipping_amount');
            $table->dropColumn('orp_date_approved');
            $table->dropColumn('orp_date_created');
            $table->dropColumn('orp_date_of_expiration');
            $table->dropColumn('orp_live_mode');
        });
    }
};

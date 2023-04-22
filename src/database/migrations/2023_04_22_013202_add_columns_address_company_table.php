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
        Schema::table('company', function (Blueprint $table) {
            $table->string('com_zipcode')->after('com_address')->nullable();
            $table->integer('com_number')->after('com_address')->nullable();
            $table->string('com_district')->after('com_address')->nullable();
            $table->string('com_city')->after('com_address')->nullable();
            $table->string('com_complement')->after('com_address')->nullable();
            $table->string('com_uf', 2)->after('com_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company', function (Blueprint $table) {
            $table->dropColumn('com_zipcode');
            $table->dropColumn('com_number');
            $table->dropColumn('com_district');
            $table->dropColumn('com_city');
            $table->dropColumn('com_complement');
            $table->dropColumn('com_uf');
        });
    }
};

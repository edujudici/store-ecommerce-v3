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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('vou_id');
            $table->string('vou_code');
            $table->float('vou_value');
            $table->date('vou_expiration_date')->nullable();
            $table->date('vou_applied_date')->nullable();
            $table->string('vou_description')->nullable();
            $table->enum('vou_status', ['active', 'inactive', 'expired', 'applied'])
                ->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};

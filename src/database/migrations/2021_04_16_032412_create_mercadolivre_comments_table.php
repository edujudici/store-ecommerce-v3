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
        Schema::create('mercadolivre_comments', function (Blueprint $table) {
            $table->bigIncrements('mec_id');
            $table->bigInteger('mec_seller_id')->unsigned();
            $table->timestamp('mec_date_created')->nullable();
            $table->string('mec_item_id')->nullable();
            $table->string('mec_status')->nullable();
            $table->string('mec_text')->nullable();
            $table->bigInteger('mec_id_secondary')->nullable();
            $table->boolean('mec_deleted_from_listing')->nullable();
            $table->boolean('mec_hold')->nullable();
            $table->string('mec_answer_date')->nullable();
            $table->string('mec_answer_status')->nullable();
            $table->string('mec_answer_text')->nullable();
            $table->bigInteger('mec_from_id')->nullable();
            $table->timestamp('mec_load_date')->nullable();
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
        Schema::dropIfExists('mercadolivre_comments');
    }
};

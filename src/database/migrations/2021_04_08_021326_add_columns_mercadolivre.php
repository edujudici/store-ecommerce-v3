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
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->string('mel_refresh_token')->nullable()->after('mel_id');
            $table->string('mel_user_id')->nullable()->after('mel_id');
            $table->string('mel_scope')->nullable()->after('mel_id');
            $table->string('mel_expires_in')->nullable()->after('mel_id');
            $table->string('mel_token_type')->nullable()->after('mel_id');
            $table->string('mel_access_token')->nullable()->after('mel_id');
            $table->string('mel_link_authorization')->nullable()->after('mel_id');
            $table->string('mel_redirect_url')->nullable()->after('mel_id');
            $table->string('mel_code_tg')->nullable()->after('mel_id');
            $table->string('mel_secret_key')->nullable()->after('mel_id');
            $table->string('mel_account_id')->nullable()->after('mel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mercadolivre', function (Blueprint $table) {
            $table->dropColumn('mel_account_id');
            $table->dropColumn('mel_secret_key');
            $table->dropColumn('mel_link_authorization');
            $table->dropColumn('mel_code_tg');
            $table->dropColumn('mel_redirect_url');
            $table->dropColumn('mel_access_token');
            $table->dropColumn('mel_token_type');
            $table->dropColumn('mel_expires_in');
            $table->dropColumn('mel_scope');
            $table->dropColumn('mel_user_id');
            $table->dropColumn('mel_refresh_token');
        });
    }
};


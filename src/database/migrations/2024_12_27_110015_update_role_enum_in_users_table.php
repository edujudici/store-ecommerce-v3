<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('users', function (Blueprint $table) {
            // Criar a nova coluna temporária
            $table->string('role_temp')->default('admin');
        });

        // Atualizar os dados existentes para a nova coluna
        DB::table('users')->update(['role_temp' => DB::raw('role')]);

        Schema::table('users', function (Blueprint $table) {
            // Remover a coluna antiga
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Criar a nova coluna enum com o tipo atualizado
            $table->enum('role', ['shopper', 'admin', 'api'])->default('admin');
        });

        // Atualizar os dados da nova coluna
        DB::table('users')->update(['role' => DB::raw('role_temp')]);

        Schema::table('users', function (Blueprint $table) {
            // Remover a coluna temporária
            $table->dropColumn('role_temp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Criar uma nova coluna temporária
            $table->string('role_temp')->default('admin');
        });

        // Atualizar os dados existentes para a coluna temporária
        DB::table('users')->update(['role_temp' => DB::raw('role')]);

        Schema::table('users', function (Blueprint $table) {
            // Remover a coluna com o tipo atualizado
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // Recriar a coluna enum original
            $table->enum('role', ['shopper', 'admin'])->default('admin');
        });

        // Atualizar os dados da coluna antiga
        DB::table('users')->update(['role' => DB::raw('role_temp')]);

        Schema::table('users', function (Blueprint $table) {
            // Remover a coluna temporária
            $table->dropColumn('role_temp');
        });
    }
};

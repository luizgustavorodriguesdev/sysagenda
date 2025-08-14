<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adiciona a coluna 'role' do tipo string
            // O valor padrão para qualquer novo usuário será 'client'
            // Adicionamos 'after ('email')' para organizar a coluna na tabela
            $table->string('role')->default('client')->after('email');
        });
    }

   /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Se precisarmos de reverter a migração, a coluna 'role' é removida.
            $table->dropColumn('role');
        });
    }
};

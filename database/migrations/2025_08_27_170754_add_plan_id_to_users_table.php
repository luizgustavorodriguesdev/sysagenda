<?php
// Em ...add_plan_id_to_users_table.php

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
            // Adiciona a coluna 'plan_id' que pode ser nula (para utilizadores que ainda não escolheram um plano).
            // A chave estrangeira liga à tabela 'plans'.
            // onDelete('set null') significa que se um plano for apagado, o utilizador não é apagado,
            // o seu plan_id apenas se torna nulo.
            $table->foreignId('plan_id')->after('role')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
        });
    }
};
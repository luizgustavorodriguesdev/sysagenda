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
        Schema::table('appointments', function (Blueprint $table) {
            // Adiciona a coluna user_id, que pode ser nula (para agendamentos feitos por não-clientes)
             // e define a chave estrangeira para a tabela users.
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {            
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /*
    * foreignId('user_id')->constrained()->onDelete('cascade'): Cria uma coluna user_id e a conecta com a coluna id da tabela users. 
    * Se um usuário for deletado, todos os negócios associados a ele também serão (onDelete('cascade')).
    */

   public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            // Chave estrangeira para a tabela 'users'
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique(); // O 'slug' deve ser único
            $table->string('branch');
            $table->timestamps(); // Cria as colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};

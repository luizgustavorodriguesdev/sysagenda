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
        Schema::create('barbers', function (Blueprint $table) {
            $table->id();

            // Chave estrangeira para ligar o barbeiro a um negócio
            $table->foreignId('business_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('email')->nullable(); // O email do barbeiro é opcional
            $table->string('phone')->nullable(); // O telefone também é opcional

            $table->timestamps(); // Colunas created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbers');
    }
};

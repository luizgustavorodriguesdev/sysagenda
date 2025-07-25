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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            // Chave estrangeira para a tabela 'businesses'
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('duration_minutes');
            $table->decimal('price', 10, 2); // 10 dígitos no total, 2 depois da vírgula
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: "Plano Básico"
            $table->string('slug')->unique(); // Ex: "basico"
            $table->string('stripe_plan_id')->unique(); // O ID correspondente do plano no Stripe
            $table->integer('price'); // Preço em cêntimos para evitar problemas com casas decimais
            $table->text('description'); // Descrição do plano
            $table->integer('barber_limit'); // O limite de barbeiros para este plano
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

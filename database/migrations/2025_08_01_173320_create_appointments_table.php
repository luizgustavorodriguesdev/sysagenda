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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');

            // NOVA COLUNA ADICIONADA:
            // O agendamento agora pertence a um barbeiro específico.
            // A chave estrangeira é anulável (nullable) por agora, mas vamos torná-la obrigatória na lógica.
            $table->foreignId('barber_id')->constrained()->onDelete('cascade');

            $table->string('customer_name');
            $table->string('customer_email');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('status')->default('confirmed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // Em ...create_barber_service_table.php

    public function up(): void
    {
        // Esta tabela não precisa de 'id' ou 'timestamps'.
        // Ela apenas contém as chaves estrangeiras para as duas tabelas que queremos ligar.
        Schema::create('barber_service', function (Blueprint $table) {
            // Chave estrangeira para a tabela 'barbers'
            $table->foreignId('barber_id')->constrained()->onDelete('cascade');
            // Chave estrangeira para a tabela 'services'
            $table->foreignId('service_id')->constrained()->onDelete('cascade');

            // Definimos uma chave primária composta para garantir que a mesma
            // combinação de barbeiro e serviço não pode ser inserida duas vezes.
            $table->primary(['barber_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barber_service');
    }
};

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
    Nota: A tabela schedules não precisa de timestamps() porque seus dados são de configuração e raramente mudam.
    */
   public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            // A versão final e correta, criando diretamente com barber_id
            $table->foreignId('barber_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');

            // Garante que não podemos ter duas entradas para o mesmo dia para o mesmo barbeiro
            $table->unique(['barber_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

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
            // Chave estrangeira para a tabela 'businesses'
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            // 0 = Domingo, 1 = Segunda, 2 = Terça, etc.
            $table->tinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');

            // Adicionando uma chave única para evitar horários duplicados para o mesmo dia no mesmo negócio
            $table->unique(['business_id', 'day_of_week']);
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

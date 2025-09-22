<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Em ...add_status_and_type_to_payments_table.php
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Guarda o tipo de pagamento (pix, dinheiro, etc.)
            $table->string('payment_type')->after('amount');
            // Guarda o status do pagamento (ex: 'active', 'inactive/refunded')
            $table->string('status')->default('active')->after('payment_type');
        });
    }
    // O método down() pode ser preenchido para reverter estas alterações
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'status']);
        });
    }
};

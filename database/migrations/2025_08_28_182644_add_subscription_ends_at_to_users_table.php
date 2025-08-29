<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Guarda a data de fim da assinatura paga.
            // É 'nullable' porque o utilizador pode não ter uma assinatura ativa.
            $table->timestamp('subscription_ends_at')->nullable()->after('trial_ends_at');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('subscription_ends_at');
        });
    }
};

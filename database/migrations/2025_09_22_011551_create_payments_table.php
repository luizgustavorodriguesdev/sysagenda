<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Liga o pagamento a um utilizador (o seu cliente)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Liga o pagamento ao plano que foi pago
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            // O valor que foi pago
            $table->decimal('amount', 10, 2);
            // A data em que o pagamento foi confirmado
            $table->date('payment_date');
            // A nova data de fim da assinatura que este pagamento concede
            $table->timestamp('new_subscription_ends_at');
            // Um campo para as suas notas (ex: "Pix, comprovativo #123")
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

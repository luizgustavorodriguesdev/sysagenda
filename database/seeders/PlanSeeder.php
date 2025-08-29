<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
use Illuminate\Support\Facades\Schema;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Plan::truncate();
        Schema::enableForeignKeyConstraints();

        Plan::create([
            'name' => 'Plano Bronze',
            'slug' => 'bronze',
            'stripe_plan_id' => 'price_1S0owYFbK0lAebwdXshQrxUS', // Exemplo
            'price' => 1990, // R$ 19,90 em cêntimos
            'description' => 'Para o profissional autônomo.',
            'barber_limit' => 1, // Limite de 1 barbeiro
        ]);

        Plan::create([
            'name' => 'Plano Prata',
            'slug' => 'prata',
            'stripe_plan_id' => 'price_1S0p1JFbK0lAebwdZcJEqgZu', // Exemplo
            'price' => 3990, // R$ 39,90 em cêntimos
            'description' => 'Ideal para pequenas equipes.',
            'barber_limit' => 3, // Limite de até 3 barbeiros
        ]);

        Plan::create([
            'name' => 'Plano Ouro',
            'slug' => 'ouro',
            'stripe_plan_id' => 'price_1S0p38FbK0lAebwdoRHFZ09l', // Exemplo
            'price' => 7990, // R$ 79,90 em cêntimos
            'description' => 'Para negócios em crescimento, sem limites.',
            'barber_limit' => 1000, // Usamos um número alto para representar "ilimitado"
        ]);
    }
}
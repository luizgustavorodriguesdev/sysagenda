<?php

namespace Database\Seeders;

// Adicione a declaração 'use' para o seu PlanSeeder
use Database\Seeders\PlanSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Esta linha diz ao Laravel para executar o método run() do nosso PlanSeeder.
        $this->call([
            PlanSeeder::class,
            // Podemos adicionar outros seeders aqui no futuro, por exemplo:
            // UserSeeder::class,
        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
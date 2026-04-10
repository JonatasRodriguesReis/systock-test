<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = User::factory(100)->create();

        $products = [];
        foreach ($users as $user) {
            // Each user will have between 50 and 100 products
            for ($i = 0; $i < rand(50, 100); $i++) {
                $products[] = [
                    'usuario_id' => $user->id,
                                        'nome'       => 'Produto ' . Str::random(5),

                    // generate ranges of 0-100, 100-500 or greater than 500
                    'preco'      => match (rand(1, 3)) {
                        1 => rand(0, 100),
                        2 => rand(100, 500),
                        3 => rand(500, 1000),
                    },
                    'descricao' => 'Descrição do produto ' . Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert products in chunks to avoid memory issues
        foreach (array_chunk($products, 1000) as $chunk) {
                        DB::table('produtos')->insert($chunk);
        }
    }
}

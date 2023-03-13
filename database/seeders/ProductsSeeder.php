<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->example()->create(['created_at' => now()->subMonths(3)]);
        Product::factory()->steam()->create(['created_at' => now()->subMonths(3)]);
        Product::factory()->amazon()->create(['created_at' => now()->subMonths(2)]);

        Product::factory()->count(5)->amazon()->create();
        Product::factory()->count(5)->steam()->create();
        Product::factory()->count(5)->example()->create();
    }
}

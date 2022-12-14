<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * run: php artisan db:seed --class=ProductSeeder
     * @return void
     */
    public function run(): void
    {
        Product::factory()->count(100)->create();
    }
}

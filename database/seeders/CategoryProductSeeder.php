<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category_product')->delete();
        DB::table('categories')->delete();
        DB::table('products')->delete();

        $createProduct = function (Category $category) {
            $category->product()->attach(
                Product::factory(rand(1, 5))->create()->pluck('id')
            );
        };

        Category::factory(7)->create()->each($createProduct);
        Category::factory(5)->withParent()->create()->each($createProduct);
    }
}

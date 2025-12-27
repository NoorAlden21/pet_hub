<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_en' => 'Food',      'name_ar' => 'طعام'],
            ['name_en' => 'Toys',      'name_ar' => 'ألعاب'],
            ['name_en' => 'Grooming',  'name_ar' => 'العناية'],
        ];

        foreach ($categories as $category) {
            ProductCategory::updateOrCreate(['name_en' => $category['name_en']], $category);
        }
    }
}

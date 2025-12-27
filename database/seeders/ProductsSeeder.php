<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PetType;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ProductCategory::all();
        $types = PetType::all();

        if ($categories->isEmpty()) return;

        $items = [
            ['en' => 'Premium Kibble', 'ar' => 'دراي فود ممتاز', 'cat' => 'Food'],
            ['en' => 'Wet Food Pack', 'ar' => 'طعام رطب', 'cat' => 'Food'],
            ['en' => 'Cat Treats', 'ar' => 'مكافآت للقطط', 'cat' => 'Food'],
            ['en' => 'Dog Treats', 'ar' => 'مكافآت للكلاب', 'cat' => 'Food'],
            ['en' => 'Bird Seeds Mix', 'ar' => 'خليط بذور للطيور', 'cat' => 'Food'],

            ['en' => 'Chew Toy', 'ar' => 'لعبة مضغ', 'cat' => 'Toys'],
            ['en' => 'Rubber Ball', 'ar' => 'كرة مطاطية', 'cat' => 'Toys'],
            ['en' => 'Laser Pointer', 'ar' => 'ليزر للعب', 'cat' => 'Toys'],
            ['en' => 'Scratching Post', 'ar' => 'عمود خدش', 'cat' => 'Toys'],
            ['en' => 'Feather Wand', 'ar' => 'عصا ريش', 'cat' => 'Toys'],

            ['en' => 'Shampoo', 'ar' => 'شامبو', 'cat' => 'Grooming'],
            ['en' => 'Brush', 'ar' => 'فرشاة', 'cat' => 'Grooming'],
            ['en' => 'Nail Clipper', 'ar' => 'قصافة أظافر', 'cat' => 'Grooming'],
            ['en' => 'Ear Cleaner', 'ar' => 'منظف أذن', 'cat' => 'Grooming'],
            ['en' => 'Flea Comb', 'ar' => 'مشط براغيث', 'cat' => 'Grooming'],
        ];

        foreach ($items as $idx => $it) {
            $category = $categories->firstWhere('name_en', $it['cat']) ?? $categories->random();

            $petTypeId = null;
            if (!$types->isEmpty() && rand(0, 1) === 1) {
                $petTypeId = $types->random()->id;
            }

            $price = rand(5, 120) + (rand(0, 99) / 100);

            Product::updateOrCreate(
                ['product_category_id' => $category->id, 'name_en' => $it['en']],
                [
                    'pet_type_id' => $petTypeId,
                    'name_ar' => $it['ar'],
                    'description' => "Seeded product: {$it['en']}",
                    'price' => $price,
                    'stock_quantity' => rand(5, 200),
                    'is_active' => true,
                ]
            );
        }
    }
}

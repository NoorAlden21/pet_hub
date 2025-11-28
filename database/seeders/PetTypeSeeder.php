<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PetType;

class PetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name_en' => 'Dog',  'name_ar' => 'كلب'],
            ['name_en' => 'Cat',  'name_ar' => 'قطة'],
            ['name_en' => 'Bird', 'name_ar' => 'طائر'],
            ['name_en' => 'Rabbit', 'name_ar' => 'أرنب'],
        ];

        foreach ($types as $type) {
            PetType::create($type);
        }
    }
}

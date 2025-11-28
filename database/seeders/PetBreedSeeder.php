<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PetBreed;
use App\Models\PetType;

class PetBreedSeeder extends Seeder
{
    public function run(): void
    {
        $breeds = [
            // Dogs
            ['type' => 'Dog',  'name_en' => 'Husky',   'name_ar' => 'هاسكي'],
            ['type' => 'Dog',  'name_en' => 'Bulldog', 'name_ar' => 'بولدوغ'],
            ['type' => 'Dog',  'name_en' => 'German Shepherd', 'name_ar' => 'جيرمن شيبرد'],

            // Cats
            ['type' => 'Cat',  'name_en' => 'Persian', 'name_ar' => 'فارسي'],
            ['type' => 'Cat',  'name_en' => 'Siamese', 'name_ar' => 'سيامي'],
            ['type' => 'Cat',  'name_en' => 'Bengal',  'name_ar' => 'بنغالي'],

            // Birds
            ['type' => 'Bird', 'name_en' => 'Parrot',  'name_ar' => 'ببغاء'],
            ['type' => 'Bird', 'name_en' => 'Canary',  'name_ar' => 'كناري'],

            // Rabbits
            ['type' => 'Rabbit', 'name_en' => 'Dutch Rabbit', 'name_ar' => 'أرنب هولندي'],
        ];

        foreach ($breeds as $breed) {
            $type = PetType::where('name_en', $breed['type'])->first();

            PetBreed::create([
                'pet_type_id' => $type->id,
                'name_en' => $breed['name_en'],
                'name_ar' => $breed['name_ar'],
            ]);
        }
    }
}

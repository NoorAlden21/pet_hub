<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\User;
use App\Models\PetBreed;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a default user
        $user = User::first() ?? User::factory()->create([
            'full_name' => 'Default User',
            'email' => 'default@example.com',
            'password' => bcrypt('password'),
        ]);

        $breeds = PetBreed::all();

        $pets = [
            ['name' => 'Rocky',   'gender' => 'male',   'description' => 'Friendly and energetic.'],
            ['name' => 'Luna',    'gender' => 'female', 'description' => 'Calm and loving.'],
            ['name' => 'Milo',    'gender' => 'male',   'description' => 'Very playful.'],
            ['name' => 'Bella',   'gender' => 'female', 'description' => 'Loves people.'],
        ];

        foreach ($pets as $i => $petData) {
            $breed = $breeds->random();

            Pet::create([
                'owner_id'      => $user->id,
                'pet_type_id'   => $breed->pet_type_id,
                'pet_breed_id'  => $breed->id,
                'name'          => $petData['name'],
                'gender'        => $petData['gender'],
                'description'   => $petData['description'],
                'date_of_birth' => now()->subYears(rand(1, 10)),
                'is_adoptable'  => rand(0, 1),
            ]);
        }
    }
}

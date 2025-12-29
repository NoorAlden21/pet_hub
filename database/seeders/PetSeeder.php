<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\User;
use App\Models\PetBreed;
use Illuminate\Support\Facades\Schema;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $nameField = Schema::hasColumn('users', 'full_name') ? 'full_name' : 'name';

        // Make sure there is at least one user
        if (User::count() === 0) {
            User::create([
                $nameField => 'Default User',
                'email' => 'default@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $users = User::where('email', '!=', 'admin@admin.com')->get();
        $breeds = PetBreed::all();

        if ($users->isEmpty() || $breeds->isEmpty()) {
            return;
        }

        $names = [
            'Rocky', 'Luna', 'Milo', 'Bella', 'Max', 'Charlie', 'Coco', 'Buddy', 'Leo', 'Nala',
            'Simba', 'Daisy', 'Lucy', 'Oscar', 'Zoe', 'Loki', 'Ruby', 'Molly', 'Toby', 'Finn',
            'Kiki', 'Shadow', 'Oreo', 'Pepper', 'Chloe', 'Poppy', 'Gizmo', 'Lucky', 'Sasha', 'Jack'
        ];

        $descriptions = [
            'Friendly and energetic.',
            'Calm and loving.',
            'Very playful.',
            'Loves people.',
            'Smart and curious.',
            'A bit shy at first, then super sweet.',
            'Great with kids.',
            'Enjoys cuddles and naps.',
        ];

        $totalPets = 30; // change to 50 if you want

        for ($i = 1; $i <= $totalPets; $i++) {
            $breed = $breeds->random();
            $owner = User::find(1);

            $baseName = $names[($i - 1) % count($names)];
            $petName = $baseName . " {$i}"; // keeps name unique-ish

            Pet::create([
                'owner_id'      => $owner->id,
                'pet_type_id'   => $breed->pet_type_id,
                'pet_breed_id'  => $breed->id,
                'name'          => $petName,
                'gender'        => rand(0, 1) ? 'male' : 'female',
                'description'   => $descriptions[array_rand($descriptions)],
                'date_of_birth' => now()->subMonths(rand(3, 120)),
                'is_adoptable'  => 1,
            ]);
        }
    }
}

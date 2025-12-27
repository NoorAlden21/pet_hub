<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Pet;

class PetImagesSeeder extends Seeder
{
    public function run(): void
    {
        $appUrl = rtrim(config('app.url') ?? env('APP_URL', ''), '/');
        if ($appUrl === '') $appUrl = 'http://localhost';

        foreach (Pet::all() as $pet) {
            $dir = "pets/{$pet->id}";
            if (!Storage::disk('public')->exists($dir)) {
                continue;
            }

            $files = Storage::disk('public')->files($dir);
            foreach ($files as $file) {
                $url = $appUrl . '/storage/' . ltrim($file, '/');

                DB::table('pet_images')->updateOrInsert(
                    ['pet_id' => $pet->id, 'path' => $file],
                    ['url' => $url, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}

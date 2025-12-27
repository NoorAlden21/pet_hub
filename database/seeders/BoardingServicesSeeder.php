<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BoardingServicesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $services = [
            [
                'name_en' => 'Grooming',
                'name_ar' => 'تنظيف وتجميل',
                'price' => 15.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Bath',
                'name_ar' => 'استحمام',
                'price' => 10.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Nail Trimming',
                'name_ar' => 'قص أظافر',
                'price' => 7.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Medication Administration',
                'name_ar' => 'إعطاء أدوية',
                'price' => 8.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Special Diet Feeding',
                'name_ar' => 'طعام خاص',
                'price' => 6.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Pick-up & Drop-off',
                'name_ar' => 'استلام وتوصيل',
                'price' => 20.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name_en' => 'Extra Playtime',
                'name_ar' => 'وقت لعب إضافي',
                'price' => 5.00,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($services as $service) {
            DB::table('boarding_services')->updateOrInsert(
                ['name_en' => $service['name_en']],
                $service
            );
        }
    }
}

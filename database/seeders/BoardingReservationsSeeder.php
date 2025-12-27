<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\PetType;
use App\Models\PetBreed;

class BoardingReservationsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('email', '!=', 'admin@admin.com')->get();
        $types = PetType::all();
        $breeds = PetBreed::all();
        $services = DB::table('boarding_services')->where('is_active', true)->get();

        if ($users->isEmpty() || $types->isEmpty() || $services->isEmpty()) return;

        $now = Carbon::now();

        // seed 12 reservations (adjust if you want)
        for ($r = 1; $r <= 12; $r++) {
            $user = $users->random();
            $type = $types->random();

            $breed = $breeds->where('pet_type_id', $type->id)->random() ?? null;

            $start = $now->copy()->subDays(rand(0, 30))->addHours(rand(0, 23));
            $hours = rand(6, 72);
            $end = $start->copy()->addHours($hours);

            $reservationId = DB::table('boarding_reservations')->insertGetId([
                'user_id' => $user->id,
                'pet_type_id' => $type->id,
                'pet_breed_id' => $breed?->id,
                'age_months' => rand(2, 96),
                'start_at' => $start,
                'end_at' => $end,
                'billable_hours' => $hours,
                'status' => collect(['pending', 'confirmed', 'completed', 'cancelled'])->random(),
                'total' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $chosenServices = collect($services)->random(min(rand(1, 4), $services->count()));
            $total = 0;

            foreach ($chosenServices as $svc) {
                $qty = rand(1, 3);
                $line = ((float) $svc->price) * $qty;
                $total += $line;

                DB::table('boarding_reservation_services')->updateOrInsert(
                    [
                        'boarding_reservation_id' => $reservationId,
                        'boarding_service_id' => $svc->id,
                    ],
                    [
                        'quantity' => $qty,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            DB::table('boarding_reservations')->where('id', $reservationId)->update([
                'total' => $total,
                'updated_at' => now(),
            ]);
        }
    }
}

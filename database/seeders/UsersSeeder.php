<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $nameField = Schema::hasColumn('users', 'full_name') ? 'full_name' : 'name';

        for ($i = 1; $i <= 10; $i++) {
            $email = "seeduser{$i}@example.com";

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    $nameField => "Seed User {$i}",
                    'password' => Hash::make('password'),
                ]
            );

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('user');
            }

            $token = $user->createToken('api')->plainTextToken;
            Log::channel('tokens')->info("User ID: {$user->id}, Email: {$email}, Token: {$token}");
        }
    }
}

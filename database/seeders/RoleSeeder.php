<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);


        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');
        $token = $admin->createToken('api')->plainTextToken;

        $user = User::create([
            'name' => 'user',
            'email' => "user@user.com",
            'password' => Hash::make('password')
        ]);

        $userToken = $user->createToken('api')->plainTextToken;

        Log::channel('tokens')->info("===================== NEW TOKENS =====================");
        Log::channel('tokens')->info("Admin ID: {$admin->id}, Token: {$token}");
        Log::channel('tokens')->info("User ID: {$user->id}, Token: {$userToken}");
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $nameField = Schema::hasColumn('users', 'full_name') ? 'full_name' : 'name';

        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                $nameField => 'admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');
        $adminToken = $admin->createToken('api')->plainTextToken;

        $user = User::updateOrCreate(
            ['email' => 'user@user.com'],
            [
                $nameField => 'user',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole('user');
        $userToken = $user->createToken('api')->plainTextToken;

        Log::channel('tokens')->info("===================== NEW TOKENS =====================");
        Log::channel('tokens')->info("Admin ID: {$admin->id}, Token: {$adminToken}");
        Log::channel('tokens')->info("User  ID: {$user->id}, Token: {$userToken}");
    }
}

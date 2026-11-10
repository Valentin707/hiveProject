<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('hive.admin.email');

        if (! is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('Set a valid HIVE_ADMIN_EMAIL before running the admin seeder.');
        }

        $admin = User::query()->where('email', $email)->first();

        if ($admin !== null) {
            $admin->forceFill(['role' => User::ROLE_ADMIN])->save();

            return;
        }

        $password = config('hive.admin.password');

        if (! is_string($password) || $password === '') {
            throw new RuntimeException('Set HIVE_ADMIN_PASSWORD before running the admin seeder.');
        }

        User::query()->create([
            'name' => config('hive.admin.name', 'Hive Administrator'),
            'email' => $email,
            'password' => $password,
            'email_verified_at' => now(),
            'role' => User::ROLE_ADMIN,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * 创建超级管理员账号
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['phone' => '13800000000'],
            [
                'name' => '超级管理员',
                'email' => 'admin@aidrama.com',
                'password' => bcrypt('admin123456'),
                'phone_verified_at' => now(),
                'email_verified_at' => now(),
                'invite_code' => strtoupper(Str::random(8)),
                'status' => 'active',
            ]
        );

        $admin->assignRole('super_admin');
    }
}

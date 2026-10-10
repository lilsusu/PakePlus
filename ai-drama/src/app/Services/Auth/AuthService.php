<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        private SmsService $smsService
    ) {}

    // 注册（手机号 + 验证码）
    public function registerByPhone(string $phone, string $code, ?string $inviteCode = null): User
    {
        $this->smsService->verify($phone, $code, 'register');

        // 手机号不能重复
        if (User::where('phone', $phone)->exists()) {
            throw new \App\Exceptions\BusinessException('该手机号已注册');
        }

        // 校验邀请码
        $inviter = null;
        if ($inviteCode) {
            $inviter = User::where('invite_code', $inviteCode)->first();
            if (! $inviter) {
                throw new \App\Exceptions\BusinessException('邀请码无效');
            }
        }

        return DB::transaction(function () use ($phone, $inviteCode, $inviter) {
            $user = User::create([
                'name' => '用户' . substr($phone, -4),
                'phone' => $phone,
                'password' => Str::random(16), // 手机号注册用户随机密码（不使用密码登录）
                'phone_verified_at' => now(),
                'invite_code' => $this->generateInviteCode(),
                'inviter_id' => $inviter?->id,
            ]);

            // 分配默认角色：普通用户
            $user->assignRole('user');

            return $user;
        });
    }

    // 登录（手机号 + 验证码）
    public function loginByPhone(string $phone, string $code): User
    {
        $this->smsService->verify($phone, $code, 'login');

        $user = User::where('phone', $phone)->first();
        if (! $user) {
            throw new \App\Exceptions\BusinessException('账号不存在，请先注册');
        }

        if ($user->isBanned()) {
            throw new \App\Exceptions\BusinessException('账号已被封禁：' . ($user->ban_reason ?: '违反平台规定'));
        }

        return $user;
    }

    // 登录（邮箱 + 密码）
    public function loginByEmail(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();
        if (! $user) {
            throw new \App\Exceptions\BusinessException('账号不存在');
        }

        if ($user->isBanned()) {
            throw new \App\Exceptions\BusinessException('账号已被封禁');
        }

        if (! password_verify($password, $user->password)) {
            throw new \App\Exceptions\BusinessException('密码错误');
        }

        return $user;
    }

    // 生成唯一邀请码
    private function generateInviteCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('invite_code', $code)->exists());

        return $code;
    }
}

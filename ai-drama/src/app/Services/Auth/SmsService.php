<?php

namespace App\Services\Auth;

use App\Jobs\SendSmsJob;
use App\Models\SmsCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SmsService
{
    // 发送验证码（按场景）
    public function send(string $phone, string $scene = 'login'): array
    {
        // 频率限制：同一手机号 60 秒内只能发一次
        $lockKey = "sms:lock:{$phone}:{$scene}";
        if (Cache::has($lockKey)) {
            throw new \App\Exceptions\BusinessException('验证码发送过于频繁，请稍后再试');
        }

        // 同一 IP 每日上限（防刷）
        $ip = request()->ip();
        $ipCountKey = "sms:ip:{$ip}:" . now()->format('Ymd');
        if (Cache::get($ipCountKey, 0) >= 20) {
            throw new \App\Exceptions\BusinessException('今日验证码发送次数已达上限');
        }

        // 生成验证码（6 位数字）
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return DB::transaction(function () use ($phone, $scene, $ip, $code) {
            // 失效之前的同手机号同场景未用验证码
            SmsCode::where('phone', $phone)
                ->where('scene', $scene)
                ->where('used', 0)
                ->update(['used' => 1]);

            $sms = SmsCode::create([
                'phone' => $phone,
                'code' => $code,
                'scene' => $scene,
                'idempotency_key' => Str::uuid()->toString(),
                'used' => 0,
                'expires_at' => now()->addMinutes(5),
                'ip_address' => $ip,
            ]);

            // 频率锁 60 秒
            Cache::put("sms:lock:{$phone}:{$scene}", 1, 60);
            Cache::increment($ipCountKey);

            // 异步发送短信
            SendSmsJob::dispatch($phone, $code, $scene);

            return [
                'id' => $sms->id,
                'ttl' => 300,
            ];
        });
    }

    // 校验验证码
    public function verify(string $phone, string $code, string $scene = 'login'): bool
    {
        $sms = SmsCode::where('phone', $phone)
            ->where('scene', $scene)
            ->where('used', 0)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $sms) {
            throw new \App\Exceptions\BusinessException('验证码已失效，请重新获取');
        }

        if (! hash_equals($sms->code, $code)) {
            throw new \App\Exceptions\BusinessException('验证码错误');
        }

        $sms->update(['used' => 1]);
        return true;
    }
}

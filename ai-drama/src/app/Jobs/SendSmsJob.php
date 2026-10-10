<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        public string $phone,
        public string $code,
        public string $scene = 'login'
    ) {}

    public function handle(): void
    {
        $provider = config('sms.provider', 'aliyun');

        // 开发环境直接记录日志（不实际发送）
        if (app()->environment('local', 'testing')) {
            Log::info("[SMS] {$this->scene} 验证码", [
                'phone' => substr($this->phone, 0, 3) . '****' . substr($this->phone, -4),
                'code' => $this->code,
            ]);
            return;
        }

        // 阿里云短信
        if ($provider === 'aliyun') {
            $this->sendViaAliyun();
        }
        // 腾讯云短信（预留）
        // elseif ($provider === 'tencent') { ... }
    }

    protected function sendViaAliyun(): void
    {
        $accessKeyId = config('sms.access_key_id');
        $accessKeySecret = config('sms.access_key_secret');
        $signName = config('sms.sign_name', 'AI短剧');
        $templateCode = config('sms.template_code');

        if (! $accessKeyId || ! $accessKeySecret) {
            Log::warning('阿里云短信未配置完整');
            return;
        }

        // 实际接入阿里云短信 SDK（此处为占位）
        // $client = new \AlibabaCloud\Client\DefaultAcsClient(...);
        // $result = $client->getAcsResponse($request);

        Log::info("阿里云短信发送", [
            'phone' => $this->phone,
            'sign' => $signName,
            'template' => $templateCode,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("短信发送失败", [
            'phone' => $this->phone,
            'scene' => $this->scene,
            'error' => $exception->getMessage(),
        ]);
    }
}

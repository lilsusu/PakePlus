<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // WebSocket 推送事件
        \App\Events\AiTaskProgress::class => [
            \App\Listeners\BroadcastAiTaskProgress::class,
        ],
        \App\Events\PointsChanged::class => [
            \App\Listeners\BroadcastPointsChanged::class,
        ],
        \App\Events\PaymentReceived::class => [
            \App\Listeners\BroadcastPaymentReceived::class,
        ],
        // 审计事件
        \OwenIt\Auditing\Events\AuditCustom::class => [
            \OwenIt\Auditing\Listeners\RecordCustomEvent::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 短信验证码表（用于注册/登录验证码）
        Schema::create('sms_codes', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index();
            $table->string('code', 6);
            $table->string('scene', 30)->default('login'); // login/register/reset
            $table->string('idempotency_key', 128)->nullable();
            $table->tinyInteger('used')->default(0);
            $table->timestamp('expires_at');
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['phone', 'scene', 'used']);
        });

        // 通知表
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type', 125);
            $table->string('notifiable_type', 125);
            $table->unsignedBigInteger('notifiable_id');
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index('read_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('sms_codes');
    }
};

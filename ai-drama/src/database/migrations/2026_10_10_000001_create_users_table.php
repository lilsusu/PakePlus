<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar', 500)->nullable();
            $table->enum('real_name_status', ['none', 'pending', 'approved', 'rejected'])->default('none');
            $table->json('real_name_data')->nullable();
            $table->enum('status', ['active', 'banned'])->default('active');
            $table->string('ban_reason', 500)->nullable();
            $table->timestamp('ban_expires_at')->nullable();
            $table->unsignedTinyInteger('member_level')->default(0);
            $table->timestamp('member_expires_at')->nullable();
            $table->string('invite_code', 32)->unique()->nullable();
            $table->unsignedBigInteger('inviter_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('phone');
            $table->index('email');
            $table->index('inviter_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

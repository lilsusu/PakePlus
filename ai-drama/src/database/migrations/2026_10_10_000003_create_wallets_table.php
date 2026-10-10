<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 积分钱包表
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->bigInteger('balance')->default(0);
            $table->bigInteger('frozen')->default(0);
            $table->bigInteger('total_recharged')->default(0);
            $table->bigInteger('total_consumed')->default(0);
            $table->bigInteger('total_refunded')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('balance');
        });

        // 积分流水表
        Schema::create('points_ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['income', 'expense', 'frozen', 'unfrozen', 'refund', 'adjust'])->index();
            $table->bigInteger('amount');
            $table->bigInteger('balance_after');
            $table->string('related_type', 125)->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('reason', 500)->nullable();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->string('idempotency_key', 128)->unique();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->index('user_id');
            $table->index(['related_type', 'related_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points_ledger');
        Schema::dropIfExists('wallets');
    }
};

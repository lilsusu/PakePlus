<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;

class Wallet extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id', 'balance', 'frozen',
        'total_recharged', 'total_consumed', 'total_refunded',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ledgers(): HasMany
    {
        return $this->hasMany(PointsLedger::class);
    }

    // 可用积分（余额 - 已冻结部分由 balance 直接表达，frozen 表示预扣）
    public function getAvailableAttribute(): int
    {
        return $this->balance;
    }

    // 检查余额是否充足
    public function canAfford(int $amount): bool
    {
        return $this->balance >= $amount;
    }
}

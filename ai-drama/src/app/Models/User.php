<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use \OwenIt\Auditing\Auditable;

    protected $guard_name = 'web';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'avatar',
        'real_name_status', 'real_name_data',
        'status', 'ban_reason', 'ban_expires_at',
        'member_level', 'member_expires_at',
        'invite_code', 'inviter_id',
        'phone_verified_at', 'email_verified_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'real_name_data',
    ];

    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'ban_expires_at' => 'datetime',
            'member_expires_at' => 'datetime',
            'real_name_data' => 'encrypted:array',
            'password' => 'hashed',
        ];
    }

    // 钱包关联
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    // 邀请人关联
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    // 手机号脱敏
    public function getPhoneMaskedAttribute(): ?string
    {
        if (! $this->phone) return null;
        return substr($this->phone, 0, 3) . '****' . substr($this->phone, -4);
    }

    // 是否被封禁
    public function isBanned(): bool
    {
        if ($this->status !== 'banned') return false;
        if ($this->ban_expires_at && $this->ban_expires_at->isPast()) {
            return false; // 临时封禁已过期
        }
        return true;
    }

    // 创建后自动创建钱包
    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->wallet()->create();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCode extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'phone', 'code', 'scene', 'idempotency_key', 'used',
        'expires_at', 'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'created_at' => 'datetime',
            'used' => 'boolean',
        ];
    }

    // 是否有效
    public function isValid(): bool
    {
        return ! $this->used && $this->expires_at->isFuture();
    }
}

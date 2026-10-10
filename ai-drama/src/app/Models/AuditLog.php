<?php

namespace App\Models;

use OwenIt\Auditing\Audit as AuditModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends AuditModel implements \OwenIt\Auditing\Contracts\Audit
{
    protected $table = 'audit_logs';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'old_values' => 'json',
            'new_values' => 'json',
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->morphTo();
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;
use OwenIt\Auditing\Contracts\Auditable;

class Role extends SpatieRole implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name', 'guard_name', 'display_name', 'description', 'data_scope',
    ];

    // 系统预设角色不可删除
    public function isSystemRole(): bool
    {
        return in_array($this->name, [
            'super_admin', 'operation', 'finance', 'support', 'auditor',
            'prompt_engineer', 'user', 'creator', 'enterprise',
        ]);
    }
}

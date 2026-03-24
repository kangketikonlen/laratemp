<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

#[Fillable(['name', 'guard_name', 'display_name', 'description', 'is_system'])]
class Role extends SpatieRole
{
    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class)
            ->withTimestamps()
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}

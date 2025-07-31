<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    // Relación con los roles
    public function roles(): BelongsToMany
    {
        
        return $this->belongsToMany(Role::class);
    }
}

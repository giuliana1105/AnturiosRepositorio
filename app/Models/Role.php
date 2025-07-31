<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // Relación con los permisos
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
    
}
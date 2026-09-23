<?php

namespace App\Policies;

use App\Models\TipoNota;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TipoNotaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver notas');
    }

    public function view(User $user, TipoNota $tipoNota): bool
    {
        return $user->can('ver notas');
    }

    public function create(User $user): bool
    {
        return $user->can('crear nota');
    }

    public function update(User $user, TipoNota $tipoNota): bool
    {
        return $user->can('editar nota');
    }

    public function delete(User $user, TipoNota $tipoNota): bool
    {
        return $user->can('eliminar nota');
    }

    public function restore(User $user, TipoNota $tipoNota): bool
    {
        return false;
    }

    public function forceDelete(User $user, TipoNota $tipoNota): bool
    {
        return false;
    }
}

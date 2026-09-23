<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venta;

class VentaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ver ventas');
    }

    public function view(User $user, Venta $venta): bool
    {
        return $user->can('ver ventas');
    }

    public function create(User $user): bool
    {
        return $user->can('registrar ventas');
    }

    public function update(User $user, Venta $venta): bool
    {
        return $user->can('registrar ventas');
    }

    public function delete(User $user, Venta $venta): bool
    {
        return $user->can('registrar ventas');
    }

    public function manageCuentasPorCobrar(User $user): bool
    {
        return $user->can('gestionar cuentas cobrar');
    }
}

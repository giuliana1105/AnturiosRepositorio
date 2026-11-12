@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Encabezado de Página -->
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Gestión de Usuarios</h3>
            <p class="page-subtitle mb-0 text-muted">Administración de cuentas, perfiles y niveles de autorización en el sistema</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="fas fa-shield-alt me-1 text-primary"></i> Ver Roles y Permisos
            </a>
            @can('crear empleado')
            <a href="{{ route('empleados.create') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                <i class="fas fa-user-plus me-1"></i> Nuevo Usuario (vía Empleado)
            </a>
            @endcan
        </div>
    </div>

    <!-- Alertas de Sesión -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(0, 151, 167, 0.12); color: var(--primary);">
                        <i class="fas fa-users fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Usuarios</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalUsuarios ?? $users->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fas fa-user-check fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Usuarios Activos</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $usuariosActivos ?? '—' }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="fas fa-key fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Clave Inicial / Pendiente</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $usuariosPendientesClave ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                        <i class="fas fa-shield-alt fs-5"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Roles Disponibles</small>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalRoles ?? count($roles ?? []) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1" 
                               placeholder="Buscar por nombre o correo electrónico..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                            <i class="fas fa-filter"></i>
                        </span>
                        <select name="role" class="form-select border-start-0 ps-1">
                            <option value="">Todos los roles</option>
                            @if(isset($roles))
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}" {{ request('role') == $r->id ? 'selected' : '' }}>
                                        {{ $r->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-3 w-100 shadow-sm">
                        <i class="fas fa-search me-1"></i> Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-pill px-3" title="Limpiar filtros">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Banner Informativo UX -->
    <div class="alert d-flex align-items-center gap-3 p-3 rounded-4 mb-4 border-0 shadow-sm" style="background: rgba(0, 151, 167, 0.08); color: #006064;">
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background: rgba(0, 151, 167, 0.15); color: var(--primary);">
            <i class="fas fa-lightbulb"></i>
        </div>
        <div style="font-size: 13.5px;" class="flex-grow-1">
            <strong>¿Cómo funciona el ciclo de usuarios?</strong> Las cuentas se crean automáticamente al dar de alta un empleado (la contraseña inicial es su cédula). Desde aquí puedes asignar o cambiar su <strong>rol y permisos</strong>. Para restablecer contraseñas o modificar datos personales, accede al módulo de <a href="{{ route('empleados.index') }}" class="fw-bold text-decoration-underline" style="color: inherit;">Empleados</a>.
        </div>
    </div>

    <!-- Tabla Principal de Usuarios -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Usuario</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Rol Principal</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Permisos Directos</th>
                            <th class="py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Estado de Seguridad</th>
                            <th class="text-end pe-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 11.5px; letter-spacing: 0.5px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($users as $user)
                            @php
                                $initial = strtoupper(substr($user->name, 0, 1));
                                $firstRole = $user->roles->first();
                                $roleName = $firstRole ? $firstRole->name : 'Sin rol';
                                
                                // Color schemes por rol
                                $badgeStyles = [
                                    'Administrador'   => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fecaca', 'icon' => 'fa-crown'],
                                    'Jefe de Bodega'  => ['bg' => '#fef3c7', 'color' => '#92400e', 'border' => '#fde68a', 'icon' => 'fa-warehouse'],
                                    'Vendedor'        => ['bg' => '#dbeafe', 'color' => '#1e40af', 'border' => '#bfdbfe', 'icon' => 'fa-shopping-bag'],
                                ];
                                $currentStyle = $badgeStyles[$roleName] ?? ['bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#e2e8f0', 'icon' => 'fa-user-tag'];
                            @endphp
                            <tr style="transition: background-color 0.15s ease;">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar-circle me-3" style="background: {{ $currentStyle['bg'] }}; color: {{ $currentStyle['color'] }};">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 14px;">{{ $user->name }}</div>
                                            <div class="text-muted d-flex align-items-center gap-1" style="font-size: 12.5px;">
                                                <i class="far fa-envelope text-muted" style="font-size: 11px;"></i>
                                                <span>{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if($user->roles->isNotEmpty())
                                        @foreach($user->roles as $role)
                                            @php
                                                $style = $badgeStyles[$role->name] ?? ['bg' => '#f1f5f9', 'color' => '#334155', 'border' => '#e2e8f0', 'icon' => 'fa-user-tag'];
                                            @endphp
                                            <span class="badge rounded-pill px-3 py-1 fw-semibold me-1" style="background: {{ $style['bg'] }}; color: {{ $style['color'] }}; border: 1px solid {{ $style['border'] }}; font-size: 12px;">
                                                <i class="fas {{ $style['icon'] }} me-1" style="font-size: 10px;"></i>{{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 11.5px;">
                                            <i class="fas fa-user-slash me-1"></i>Sin rol
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @php $directPerms = $user->getDirectPermissions(); @endphp
                                    @if($directPerms->isNotEmpty())
                                        <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(0, 151, 167, 0.12); color: var(--primary); font-size: 11.5px; border: 1px solid rgba(0, 151, 167, 0.25);">
                                            <i class="fas fa-plus-circle me-1"></i>{{ $directPerms->count() }} personalizado{{ $directPerms->count() > 1 ? 's' : '' }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size: 12px;">
                                            <i class="fas fa-minus text-muted opacity-50 me-1"></i>Hereda solo de su rol
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($user->must_change_password ?? false)
                                        <span class="badge rounded-pill px-3 py-1" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 11.5px;" title="El usuario debe cambiar su clave inicial al ingresar">
                                            <i class="fas fa-shield-alt me-1"></i>Clave Inicial
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-1" style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 11.5px;">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 py-3">
                                    @can('editar usuario')
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-xs" title="Administrar rol y permisos">
                                        <i class="fas fa-user-shield me-1"></i> Configurar
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9; color: #94a3b8;">
                                            <i class="fas fa-users-slash fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No se encontraron usuarios</h6>
                                        <p class="text-muted mb-3" style="font-size: 13.5px;">Intenta cambiar los términos de búsqueda o registra un nuevo empleado.</p>
                                        @if(request()->hasAny(['search', 'role']))
                                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                <i class="fas fa-undo me-1"></i> Restablecer filtros
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($users, 'links') && $users->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-center bg-white">
                    {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.user-avatar-circle {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.shadow-xs {
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
</style>
@endsection

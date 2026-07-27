@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="page-header">
        <div>
            <h3>Gestión de Usuarios</h3>
            <p class="page-subtitle">Administración de accesos y cuentas del sistema</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Usuario</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder me-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="color: var(--foreground);">{{ $user->name }}</div>
                                            <div class="text-muted" style="font-size: 13px;">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-icon btn-info" title="Editar" style="color: white;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-icon btn-danger delete-btn" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-5 text-muted">
                                    <div class="mb-3">
                                        <i class="fas fa-users" style="font-size: 48px; color: var(--border-color);"></i>
                                    </div>
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($users, 'links'))
                <div class="p-3 border-top">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: var(--accent-subtle);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            if(confirm('¿Está seguro de que desea eliminar este usuario?')) {
                this.closest('form').submit();
            }
        });
    });
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="cp-wrapper">
    <div class="cp-card">
        {{-- Header con info del usuario --}}
        <div class="cp-header">
            <div class="cp-user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="cp-user-info">
                <h5 class="cp-title"><i class="fas fa-key me-2"></i>Cambiar Contraseña</h5>
                <span class="cp-user-name">{{ auth()->user()->name }}</span>
                <span class="cp-user-email">{{ auth()->user()->email }}</span>
                <span class="cp-user-badge">{{ auth()->user()->cargoNombre() }}</span>
            </div>
        </div>

        {{-- Alerta obligatoria --}}
        @if(auth()->user()->must_change_password)
        <div class="cp-alert cp-alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Debes cambiar tu contraseña antes de acceder al sistema.</span>
        </div>
        @endif

        {{-- Errores --}}
        @if ($errors->any())
        <div class="cp-alert cp-alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Éxito --}}
        @if (session('success'))
        <div class="cp-alert cp-alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('password.change') }}" method="POST" class="cp-form">
            @csrf
            <div class="cp-field">
                <label for="current_password"><i class="fas fa-lock"></i> Contraseña Actual</label>
                <input type="password" name="current_password" id="current_password" 
                       class="@error('current_password') cp-input-error @enderror" 
                       required placeholder="Ingresa tu contraseña actual">
                @if(auth()->user()->must_change_password)
                <small class="cp-hint"><i class="fas fa-info-circle"></i> Si fue restablecida, usa tu número de cédula</small>
                @endif
            </div>

            <div class="cp-field-row">
                <div class="cp-field">
                    <label for="password"><i class="fas fa-key"></i> Nueva Contraseña</label>
                    <input type="password" name="password" id="password" required 
                           placeholder="Mínimo 8 caracteres">
                </div>
                <div class="cp-field">
                    <label for="password_confirmation"><i class="fas fa-lock"></i> Confirmar</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           required placeholder="Repite la nueva contraseña">
                </div>
            </div>

            <button type="submit" class="cp-btn-submit">
                <i class="fas fa-key me-2"></i> Actualizar Contraseña
            </button>
        </form>

        {{-- Cerrar sesión --}}
        @if(auth()->user()->must_change_password)
        <form action="{{ route('logout') }}" method="POST" class="cp-logout">
            @csrf
            <button type="submit" class="cp-btn-logout">
                <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
            </button>
        </form>
        @endif
    </div>
</div>

<style>
/* ===== Reset scroll ===== */
html, body, #app {
    height: auto !important;
    min-height: 100vh;
    overflow-y: auto !important;
    margin: 0;
    padding: 0;
}
.container-fluid {
    padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
}

/* ===== Wrapper ===== */
.cp-wrapper {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    min-height: 100vh;
    padding: 30px 16px;
    background: var(--bg-base, #faf9f7);
}

/* ===== Card ===== */
.cp-card {
    width: 100%;
    max-width: 480px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 24px;
}

/* ===== Header ===== */
.cp-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f0eeeb;
    margin-bottom: 16px;
}
.cp-user-avatar {
    width: 44px;
    height: 44px;
    min-width: 44px;
    border-radius: 50%;
    background: var(--brand-primary, #0097a7);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.cp-user-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
    overflow: hidden;
}
.cp-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--brand-dark, #2c2925);
    margin: 0;
}
.cp-user-name {
    font-size: 0.82rem;
    font-weight: 600;
    color: #555;
}
.cp-user-email {
    font-size: 0.75rem;
    color: #999;
}
.cp-user-badge {
    display: inline-block;
    margin-top: 2px;
    padding: 1px 8px;
    border-radius: 20px;
    background: var(--brand-primary, #0097a7);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 600;
    width: fit-content;
}

/* ===== Alerts ===== */
.cp-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 0.82rem;
    margin-bottom: 14px;
}
.cp-alert i {
    margin-top: 2px;
    flex-shrink: 0;
}
.cp-alert-warning {
    background: #fff8e1;
    color: #8b6914;
    border: 1px solid #ffe082;
}
.cp-alert-danger {
    background: #fdecea;
    color: #b71c1c;
    border: 1px solid #ef9a9a;
}
.cp-alert-success {
    background: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #a5d6a7;
}

/* ===== Form ===== */
.cp-form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}
.cp-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
}
.cp-field label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #555;
}
.cp-field label i {
    color: var(--brand-primary, #0097a7);
    font-size: 0.75rem;
    margin-right: 4px;
}
.cp-field input {
    padding: 10px 16px;
    border: 2px solid #e9ecef;
    border-radius: 50rem;
    font-size: 0.88rem;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit;
    width: 100%;
}
.cp-field input:focus {
    border-color: var(--brand-primary, #0097a7);
    box-shadow: 0 0 0 3px rgba(0, 151, 167, 0.15);
}
.cp-input-error {
    border-color: #e53935 !important;
}
.cp-hint {
    font-size: 0.72rem;
    color: #888;
    margin-top: 2px;
}
.cp-hint i {
    color: var(--brand-primary, #0097a7);
}

/* ===== Row de campos lado a lado ===== */
.cp-field-row {
    display: flex;
    gap: 12px;
}

/* ===== Botón principal ===== */
.cp-btn-submit {
    margin-top: 6px;
    padding: 12px 24px;
    background: var(--brand-primary, #0097a7);
    color: #fff;
    border: none;
    border-radius: 50rem;
    font-size: 0.92rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    font-family: inherit;
    width: 100%;
}
.cp-btn-submit:hover {
    background: var(--brand-primary-dark, #00796b);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(0, 151, 167, 0.35);
}

/* ===== Logout ===== */
.cp-logout {
    text-align: center;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #f0eeeb;
}
.cp-btn-logout {
    background: transparent;
    border: 1.5px solid #ccc;
    border-radius: 50rem;
    padding: 8px 20px;
    font-size: 0.8rem;
    color: #888;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
}
.cp-btn-logout:hover {
    border-color: #999;
    color: #555;
}

/* ===== Responsive ===== */
@media (max-width: 540px) {
    .cp-wrapper {
        padding: 16px 10px;
    }
    .cp-card {
        padding: 18px;
    }
    .cp-field-row {
        flex-direction: column;
        gap: 14px;
    }
}
</style>
@endsection
@extends('layouts.admin')

@section('title', 'Configuración')
@section('page-title', 'Configuración del Sistema')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2 text-primary"></i>
                        Preferencias del Sistema
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Información del Usuario -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="fas fa-user-circle me-2 text-primary"></i>
                                        Información de la Cuenta
                                    </h6>
                                    <p class="mb-2"><strong>Usuario:</strong> {{ $user->name }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p class="mb-2"><strong>Rol:</strong> 
                                        @switch($user->role)
                                            @case('administrador')
                                                <span class="badge bg-danger">Administrador</span>
                                                @break
                                            @case('supervisor')
                                                <span class="badge bg-warning">Supervisor</span>
                                                @break
                                            @case('almacen')
                                                <span class="badge bg-info">Almacén</span>
                                                @break
                                            @case('consulta')
                                                <span class="badge bg-success">Consulta</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                        @endswitch
                                    </p>
                                    <p class="mb-0"><strong>Miembro desde:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración Regional -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="fas fa-globe me-2 text-success"></i>
                                        Configuración Regional
                                    </h6>
                                    <p class="mb-2"><strong>Zona Horaria:</strong> America/Lima (GMT-5)</p>
                                    <p class="mb-2"><strong>Formato de Fecha:</strong> DD/MM/YYYY</p>
                                    <p class="mb-2"><strong>Idioma:</strong> Español</p>
                                    <p class="mb-0"><strong>Moneda:</strong> Soles (S/)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Seguridad -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="fas fa-shield-alt me-2 text-warning"></i>
                                        Seguridad
                                    </h6>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('profile.password') }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-key me-2"></i>Cambiar Contraseña
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preferencias del Sistema -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="fas fa-sliders-h me-2 text-info"></i>
                                        Preferencias del Sistema
                                    </h6>
                                    <form id="preferencesForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Tema de Interfaz</label>
                                            <select class="form-select form-select-sm" id="theme" name="theme">
                                                <option value="claro" {{ $user->theme === 'claro' ? 'selected' : '' }}>Claro</option>
                                                <option value="oscuro" {{ $user->theme === 'oscuro' ? 'selected' : '' }}>Oscuro</option>
                                                <option value="automatico" {{ $user->theme === 'automatico' ? 'selected' : '' }}>Automático</option>
                                            </select>
                                            <small class="text-muted">Próximamente</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Notificaciones</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="notifications" name="notifications_enabled" {{ $user->notifications_enabled ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="notifications">
                                                    Activar notificaciones del sistema
                                                </label>
                                            </div>
                                            <small class="text-muted">Próximamente</small>
                                        </div>
                                        <div id="preferenceMessage" class="alert d-none"></div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Sistema -->
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="fas fa-info-circle me-2 text-secondary"></i>
                                        Información del Sistema
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <p class="mb-0"><strong>Sistema:</strong> Control de Inventario</p>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <p class="mb-0"><strong>Versión:</strong> 1.0.0</p>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <p class="mb-0"><strong>Laravel:</strong> {{ app()->version() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                        </a>
                        <a href="{{ route('profile.show') }}" class="btn btn-primary">
                            <i class="fas fa-user me-2"></i>Ver Mi Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeSelect = document.getElementById('theme');
    const notificationsCheckbox = document.getElementById('notifications');
    const preferenceMessage = document.getElementById('preferenceMessage');

    function updatePreferences() {
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('theme', themeSelect.value);
        formData.append('notifications_enabled', notificationsCheckbox.checked ? '1' : '0');

        fetch('{{ route('settings.preferences.update') }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cambiar el tema inmediatamente
                document.documentElement.setAttribute('data-theme', themeSelect.value);
                
                preferenceMessage.className = 'alert alert-success alert-dismissible fade show';
                preferenceMessage.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                preferenceMessage.classList.remove('d-none');
                
                setTimeout(() => {
                    preferenceMessage.classList.add('d-none');
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            preferenceMessage.className = 'alert alert-danger alert-dismissible fade show';
            preferenceMessage.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                Error al actualizar las preferencias.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            preferenceMessage.classList.remove('d-none');
        });
    }

    themeSelect.addEventListener('change', updatePreferences);
    notificationsCheckbox.addEventListener('change', updatePreferences);
});
</script>
@endpush

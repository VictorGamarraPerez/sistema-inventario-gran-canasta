@extends('layouts.admin')

@section('page-title', 'Editar Usuario')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar Usuario</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Déjalo en blanco si no deseas cambiarla</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rol del Usuario <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="">Seleccionar rol...</option>
                                <option value="administrador" {{ old('role', $user->role) == 'administrador' ? 'selected' : '' }}>
                                    Administrador
                                </option>
                                <option value="almacen" {{ old('role', $user->role) == 'almacen' ? 'selected' : '' }}>
                                    Almacén
                                </option>
                                <option value="supervisor" {{ old('role', $user->role) == 'supervisor' ? 'selected' : '' }}>
                                    Supervisor
                                </option>
                                <option value="consulta" {{ old('role', $user->role) == 'consulta' ? 'selected' : '' }}>
                                    Consulta
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Estado del Usuario</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="active" name="active" 
                                       {{ old('active', $user->active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">
                                    <i class="fas fa-check-circle text-success me-1"></i>Activo
                                </label>
                            </div>
                            <div class="form-text">Los usuarios inactivos no podrán acceder al sistema</div>
                        </div>

                        @if($user->id === Auth::id())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Estás editando tu propia cuenta. Ten cuidado al cambiar tu rol o desactivar tu cuenta.
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

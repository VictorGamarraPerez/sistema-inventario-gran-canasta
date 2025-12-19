@extends('layouts.admin')

@section('title', 'Mi Perfil')
@section('page-title', 'Mi Perfil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Información del Perfil
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-12 text-center mb-4">
                            <div class="profile-avatar mx-auto mb-3" style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 48px; color: white; font-weight: bold;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-shield-alt me-1"></i>
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
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Nombre Completo</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                <i class="fas fa-user me-2 text-primary"></i>{{ $user->name }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Correo Electrónico</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                <i class="fas fa-envelope me-2 text-primary"></i>{{ $user->email }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Rol en el Sistema</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                @switch($user->role)
                                    @case('administrador')
                                        Administrador
                                        @break
                                    @case('supervisor')
                                        Supervisor
                                        @break
                                    @case('almacen')
                                        Almacén
                                        @break
                                    @case('consulta')
                                        Consulta
                                        @break
                                    @default
                                        {{ ucfirst($user->role) }}
                                @endswitch
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Estado</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                @if($user->active)
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Activo</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Inactivo</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Miembro desde</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                <i class="fas fa-calendar me-2 text-primary"></i>{{ $user->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Última actualización</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                <i class="fas fa-clock me-2 text-primary"></i>{{ $user->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2 justify-content-end">
                        @if(Auth::user()->role === 'administrador')
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Editar Perfil
                        </a>
                        @endif
                        <a href="{{ route('profile.password') }}" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Cambiar Contraseña
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Verificación de Código - La Gran Canasta')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="company-logo">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h2>Verificación en 2 Pasos</h2>
                    <p>Seguridad Adicional</p>
                </div>
                
                <div class="auth-body">
                    <h5 class="text-center mb-3">Ingresa el Código de Verificación</h5>
                    
                    <p class="text-center text-muted mb-4">
                        <i class="fas fa-envelope-open-text me-2"></i>
                        Hemos enviado un código de 6 dígitos a tu correo electrónico. 
                        Por favor, ingrésalo a continuación.
                    </p>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Error:</strong>
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('verification.verify') }}" id="verificationForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="code" class="form-label text-center d-block">
                                <i class="fas fa-key me-1"></i> Código de Verificación
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   placeholder="000000"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   style="font-size: 24px; letter-spacing: 10px; font-weight: bold;"
                                   required 
                                   autofocus>
                            @error('code')
                                <div class="text-danger mt-2 text-center">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block text-center mt-2">
                                El código expira en 10 minutos
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-check-circle me-2"></i> Verificar Código
                        </button>
                    </form>
                    
                    <div class="text-center">
                        <p class="mb-2">¿No recibiste el código?</p>
                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none">
                                <i class="fas fa-redo-alt me-1"></i> Reenviar Código
                            </button>
                        </form>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Volver al Login
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <small class="text-white">
                    <i class="fas fa-lock me-1"></i> 
                    Tu seguridad es nuestra prioridad
                </small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-focus y validación del código
    document.getElementById('code').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length === 6) {
            document.getElementById('verificationForm').submit();
        }
    });
</script>
@endpush
@endsection

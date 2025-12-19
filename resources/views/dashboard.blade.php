@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px;">
            <div class="card-body p-4">
                <h2 class="mb-2"><i class="fas fa-hand-sparkles me-2"></i>¡Bienvenido, {{ Auth::user()->name }}!</h2>
                <p class="mb-2 opacity-90">Sistema de Control de Inventario - La Gran Canasta</p>
                <small class="opacity-75"><i class="fas fa-calendar-alt me-2"></i>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-card-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <p class="stat-card-title">Total de Productos en Stock</p>
            <h3 class="stat-card-value">{{ number_format($totalProductsInStock) }}</h3>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card" style="border-left-color: #f39c12;">
            <div class="stat-card-icon" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <p class="stat-card-title">Productos Bajos en Inventario</p>
            <h3 class="stat-card-value">{{ $lowStockProducts }}</h3>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card" style="border-left-color: #27ae60;">
            <div class="stat-card-icon" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%);">
                <i class="fas fa-arrow-down"></i>
            </div>
            <p class="stat-card-title">Entradas Recientes</p>
            <h3 class="stat-card-value">{{ $recentEntries }}</h3>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card" style="border-left-color: #e74c3c;">
            <div class="stat-card-icon" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                <i class="fas fa-arrow-up"></i>
            </div>
            <p class="stat-card-title">Salidas Recientes</p>
            <h3 class="stat-card-value">{{ $recentExits }}</h3>
        </div>
    </div>
</div>

<!-- Quick Access Cards -->
<div class="row">
    <div class="col-12 mb-3">
        <h4 class="fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>Acceso Rápido</h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('products.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 48px; color: #667eea;">
                        <i class="fas fa-box"></i>
                    </div>
                    <h5 class="card-title text-dark mb-2">Productos</h5>
                    <p class="card-text text-muted small">Gestión completa de productos</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('entries.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 48px; color: #27ae60;">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <h5 class="card-title text-dark mb-2">Entradas</h5>
                    <p class="card-text text-muted small">Registro de entradas al inventario</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('exits.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 48px; color: #e74c3c;">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <h5 class="card-title text-dark mb-2">Salidas</h5>
                    <p class="card-text text-muted small">Control de salidas de productos</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <a href="{{ route('reports.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="mb-3" style="font-size: 48px; color: #3498db;">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h5 class="card-title text-dark mb-2">Reportes</h5>
                    <p class="card-text text-muted small">Análisis y reportes detallados</p>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush

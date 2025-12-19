@extends('layouts.admin')

@section('page-title', 'Historial de Salidas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-sign-out-alt me-2"></i>Salidas de Productos</h2>
        @if(Auth::user()->role !== 'consulta')
        <a href="{{ route('exits.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Salida
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('exits.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nombre o código del producto">
                </div>
                <div class="col-md-3">
                    <label for="reason" class="form-label">Motivo</label>
                    <select class="form-select" id="reason" name="reason">
                        <option value="">Todos</option>
                        <option value="venta" {{ request('reason') == 'venta' ? 'selected' : '' }}>Venta</option>
                        <option value="devolución" {{ request('reason') == 'devolución' ? 'selected' : '' }}>Devolución</option>
                        <option value="pérdida" {{ request('reason') == 'pérdida' ? 'selected' : '' }}>Pérdida</option>
                        <option value="merma" {{ request('reason') == 'merma' ? 'selected' : '' }}>Merma</option>
                        <option value="donación" {{ request('reason') == 'donación' ? 'selected' : '' }}>Donación</option>
                        <option value="otro" {{ request('reason') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Desde</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Hasta</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('exits.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Salidas -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Cantidad</th>
                            <th>Motivo</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exits as $exit)
                            <tr>
                                <td>{{ $exit->id }}</td>
                                <td>{{ $exit->product->name }}</td>
                                <td>{{ $exit->product->code }}</td>
                                <td>
                                    <span class="badge bg-danger">{{ $exit->quantity }}</span>
                                </td>
                                <td>
                                    @switch($exit->reason)
                                        @case('venta')
                                            <span class="badge bg-success">Venta</span>
                                            @break
                                        @case('devolución')
                                            <span class="badge bg-info">Devolución</span>
                                            @break
                                        @case('pérdida')
                                            <span class="badge bg-danger">Pérdida</span>
                                            @break
                                        @case('merma')
                                            <span class="badge bg-warning">Merma</span>
                                            @break
                                        @case('donación')
                                            <span class="badge bg-primary">Donación</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($exit->reason) }}</span>
                                    @endswitch
                                </td>
                                <td>{{ \Carbon\Carbon::parse($exit->exit_date)->format('d/m/Y') }}</td>
                                <td>{{ $exit->user->name }}</td>
                                <td>{{ $exit->observations ? Str::limit($exit->observations, 30) : '-' }}</td>
                                <td>
                                    @if(Auth::user()->role !== 'consulta')
                                    <a href="{{ route('exits.edit', $exit) }}" class="btn btn-primary btn-sm me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('exits.destroy', $exit) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('¿Está seguro? Esto revertirá el stock del producto.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <span class="badge bg-secondary">Solo lectura</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No se encontraron salidas registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $exits->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

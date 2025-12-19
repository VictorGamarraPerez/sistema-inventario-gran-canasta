@extends('layouts.admin')

@section('page-title', 'Reportes de Inventario')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chart-bar me-2"></i>Reportes</h2>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Desde</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ $dateFrom }}" required>
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Hasta</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ $dateTo }}" required>
                </div>
                <div class="col-md-2">
                    <label for="movement_type" class="form-label">Entrada/Salida</label>
                    <select class="form-select" id="movement_type" name="movement_type">
                        <option value="all" {{ $movementType == 'all' ? 'selected' : '' }}>Todos</option>
                        <option value="entry" {{ $movementType == 'entry' ? 'selected' : '' }}>Solo Entradas</option>
                        <option value="exit" {{ $movementType == 'exit' ? 'selected' : '' }}>Solo Salidas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="category_id" class="form-label">Categoría</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export"></i> Exportar
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                            <li>
                                <a class="dropdown-item" href="#" onclick="exportReport('excel')">
                                    <i class="fas fa-file-excel text-success me-2"></i> Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="exportReport('pdf')">
                                    <i class="fas fa-file-pdf text-danger me-2"></i> PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen de Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-card-icon bg-success">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="stat-card-title">Total Entradas</div>
                <div class="stat-card-value">{{ number_format($data['totalEntries']) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-card-icon bg-danger">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="stat-card-title">Total Salidas</div>
                <div class="stat-card-value">{{ number_format($data['totalExits']) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-card-icon bg-info">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-card-title">Total Movimientos</div>
                <div class="stat-card-value">{{ number_format($data['totalMovements']) }}</div>
            </div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="row mb-4">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Movimientos del Período</h5>
                </div>
                <div class="card-body">
                    <canvas id="movementsChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Productos Más Movidos</h5>
                </div>
                <div class="card-body">
                    <canvas id="topProductsChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfica de Categorías -->
    @if(count($data['movementsByCategory']['labels']) > 0)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Movimientos por Categoría</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoriesChart" style="height: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de Resultados -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Tabla de Resultados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Movimiento</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Usuario</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['movements'] as $movement)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($movement['date'])->format('d/m/Y') }}</td>
                                <td>
                                    @if($movement['type'] == 'Entrada')
                                        <span class="badge bg-success">
                                            <i class="fas fa-arrow-down me-1"></i>Entrada
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-arrow-up me-1"></i>Salida
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $movement['product'] }}</td>
                                <td>{{ $movement['category'] }}</td>
                                <td><strong>{{ $movement['quantity'] }}</strong></td>
                                <td>{{ $movement['user'] }}</td>
                                <td>{{ $movement['observations'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No se encontraron movimientos en el período seleccionado</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .stat-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .stat-card-title {
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
    }

    .stat-card-value {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Colores del tema
    const primaryColor = '#667eea';
    const secondaryColor = '#764ba2';
    const successColor = '#28a745';
    const dangerColor = '#dc3545';
    const infoColor = '#17a2b8';
    
    // Gráfica de Movimientos por Día
    const ctxMovements = document.getElementById('movementsChart');
    if (ctxMovements) {
        const movementsData = {
            labels: @json($data['movementsByDay']['labels']),
            datasets: []
        };

        @if($movementType == 'all' || $movementType == 'entry')
        movementsData.datasets.push({
            label: 'Entradas',
            data: @json($data['movementsByDay']['entries']),
            backgroundColor: 'rgba(40, 167, 69, 0.6)',
            borderColor: successColor,
            borderWidth: 2
        });
        @endif

        @if($movementType == 'all' || $movementType == 'exit')
        movementsData.datasets.push({
            label: 'Salidas',
            data: @json($data['movementsByDay']['exits']),
            backgroundColor: 'rgba(220, 53, 69, 0.6)',
            borderColor: dangerColor,
            borderWidth: 2
        });
        @endif

        new Chart(ctxMovements, {
            type: 'bar',
            data: movementsData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Gráfica de Productos Más Movidos
    const ctxTopProducts = document.getElementById('topProductsChart');
    if (ctxTopProducts) {
        new Chart(ctxTopProducts, {
            type: 'doughnut',
            data: {
                labels: @json($data['topProducts']['labels']),
                datasets: [{
                    data: @json($data['topProducts']['data']),
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#4facfe',
                        '#43e97b'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    }

    // Gráfica de Categorías
    const ctxCategories = document.getElementById('categoriesChart');
    if (ctxCategories) {
        new Chart(ctxCategories, {
            type: 'bar',
            data: {
                labels: @json($data['movementsByCategory']['labels']),
                datasets: [{
                    label: 'Cantidad Total',
                    data: @json($data['movementsByCategory']['data']),
                    backgroundColor: 'rgba(102, 126, 234, 0.6)',
                    borderColor: primaryColor,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Función de exportación
    function exportReport(format) {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        let url = '';
        if (format === 'excel') {
            url = '{{ route("reports.export.excel") }}?' + params.toString();
        } else if (format === 'pdf') {
            url = '{{ route("reports.export.pdf") }}?' + params.toString();
        }
        
        window.location.href = url;
        return false;
    }
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('title', 'Productos')
@section('page-title', 'Listado de Productos')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="fas fa-box me-2"></i>Gestión de Productos</h4>
            </div>
            @if(Auth::user()->role !== 'consulta')
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Producto
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('products.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Buscar por código o nombre..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <div class="form-check mt-2">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="low_stock" 
                               value="1"
                               id="lowStock"
                               {{ request('low_stock') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="lowStock">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                            Solo stock bajo
                        </label>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de productos -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($products->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">No hay productos registrados</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear primer producto
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre del Producto</th>
                            <th>Categoría</th>
                            <th class="text-center">Stock Actual</th>
                            <th class="text-end">Precio</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $product->code }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}"
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <span>{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $product->category->name }}</span>
                                </td>
                                <td class="text-center">
                                    @if($product->isLowStock())
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong>S/ {{ number_format($product->sale_price, 2) }}</strong>
                                </td>
                                <td class="text-center">
                                    @if(Auth::user()->role !== 'consulta')
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                    <span class="badge bg-secondary">Solo lectura</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }} de {{ $products->total() }} productos
                </div>
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

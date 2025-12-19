@extends('layouts.admin')

@section('title', 'Entradas')
@section('page-title', 'Historial de Entradas')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="fas fa-arrow-down me-2 text-success"></i>Registro de Entradas</h4>
            </div>
            @if(Auth::user()->role !== 'consulta')
            <a href="{{ route('entries.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Nueva Entrada
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('entries.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Buscar producto o proveedor..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select name="supplier_id" class="form-select">
                        <option value="">Todos los proveedores</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" 
                                    {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <input type="date" 
                           name="date_from" 
                           class="form-control"
                           placeholder="Desde"
                           value="{{ request('date_from') }}">
                </div>
                
                <div class="col-md-2">
                    <input type="date" 
                           name="date_to" 
                           class="form-control"
                           placeholder="Hasta"
                           value="{{ request('date_to') }}">
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

<!-- Tabla de entradas -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($entries->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">No hay entradas registradas</p>
                <a href="{{ route('entries.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Registrar primera entrada
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center" style="min-width: 120px;">Total</th>
                            <th>Registrado por</th>
                            <th>Observaciones</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $entry)
                            <tr>
                                <td>
                                    <strong>{{ $entry->entry_date->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $entry->entry_date->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div>
                                        <strong class="text-primary">{{ $entry->product->code }}</strong>
                                        <br>
                                        {{ $entry->product->name }}
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-truck text-muted me-1"></i>
                                    <a href="#" 
                                       class="text-decoration-none text-dark supplier-link" 
                                       style="cursor: pointer;"
                                       data-bs-toggle="modal" 
                                       data-bs-target="#supplierModal"
                                       data-id="{{ $entry->supplier->id }}"
                                       data-name="{{ $entry->supplier->name }}"
                                       data-email="{{ $entry->supplier->email ?? 'N/A' }}"
                                       data-phone="{{ $entry->supplier->phone ?? 'N/A' }}"
                                       data-address="{{ $entry->supplier->address ?? 'N/A' }}">
                                        {{ $entry->supplier->name }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success" style="font-size: 14px;">
                                        +{{ $entry->quantity }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($entry->total)
                                        <strong>S/ {{ number_format($entry->total, 2) }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-user text-muted me-1"></i>
                                    {{ $entry->user->name }}
                                </td>
                                <td>
                                    @if($entry->observations)
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                              title="{{ $entry->observations }}">
                                            {{ $entry->observations }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(Auth::user()->role !== 'consulta')
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('entries.edit', $entry) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('entries.destroy', $entry) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro? Esta acción revertirá el stock del producto.')">
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
                    Mostrando {{ $entries->firstItem() }} a {{ $entries->lastItem() }} de {{ $entries->total() }} entradas
                </div>
                {{ $entries->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Estadísticas rápidas -->
@if(!$entries->isEmpty())
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-box-open fa-2x text-success mb-2"></i>
                <h5 class="mb-0">{{ $entries->sum('quantity') }}</h5>
                <small class="text-muted">Total de Unidades Ingresadas</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fa-2x text-primary mb-2"></i>
                <h5 class="mb-0">{{ $entries->total() }}</h5>
                <small class="text-muted">Total de Entradas Registradas</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h5 class="mb-0">{{ $entries->first()->entry_date->format('d/m/Y') }}</h5>
                <small class="text-muted">Última Entrada Registrada</small>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal para ver información del proveedor -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="supplierModalLabel">
                    <i class="fas fa-truck me-2 text-primary"></i>
                    Información del Proveedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="supplier-id">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-building me-2"></i>Nombre
                                </h6>
                                <h5 class="mb-0 supplier-view" id="supplier-name">-</h5>
                                <input type="text" class="form-control supplier-edit d-none" id="supplier-name-input">
                                <div class="invalid-feedback" id="supplier-name-error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </h6>
                                <p class="mb-0 supplier-view" id="supplier-email">-</p>
                                <input type="email" class="form-control supplier-edit d-none" id="supplier-email-input">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-phone me-2"></i>Teléfono
                                </h6>
                                <p class="mb-0 supplier-view" id="supplier-phone">-</p>
                                <input type="text" class="form-control supplier-edit d-none" id="supplier-phone-input">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>Dirección
                                </h6>
                                <p class="mb-0 supplier-view" id="supplier-address">-</p>
                                <textarea class="form-control supplier-edit d-none" id="supplier-address-input" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="supplier-view-buttons">
                    <button type="button" class="btn btn-primary" id="editSupplierBtn">
                        <i class="fas fa-edit me-2"></i>Editar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cerrar
                    </button>
                </div>
                <div class="supplier-edit-buttons d-none">
                    <button type="button" class="btn btn-success" id="saveSupplierBtn">
                        <i class="fas fa-save me-2"></i>Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" id="cancelEditBtn">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentSupplierId = null;
    let originalData = {};
    
    // Manejar clic en proveedor para mostrar información
    document.querySelectorAll('.supplier-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Obtener datos del proveedor
            currentSupplierId = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const phone = this.getAttribute('data-phone');
            const address = this.getAttribute('data-address');
            
            // Guardar datos originales
            originalData = { name, email, phone, address };
            
            // Actualizar el modal con la información
            document.getElementById('supplier-id').value = currentSupplierId;
            document.getElementById('supplier-name').textContent = name;
            document.getElementById('supplier-email').textContent = email;
            document.getElementById('supplier-phone').textContent = phone;
            document.getElementById('supplier-address').textContent = address;
            
            // Asegurar que está en modo vista
            setViewMode();
        });
    });
    
    // Botón editar
    document.getElementById('editSupplierBtn').addEventListener('click', function() {
        setEditMode();
    });
    
    // Botón cancelar
    document.getElementById('cancelEditBtn').addEventListener('click', function() {
        // Restaurar datos originales
        document.getElementById('supplier-name').textContent = originalData.name;
        document.getElementById('supplier-email').textContent = originalData.email;
        document.getElementById('supplier-phone').textContent = originalData.phone;
        document.getElementById('supplier-address').textContent = originalData.address;
        
        setViewMode();
    });
    
    // Botón guardar
    document.getElementById('saveSupplierBtn').addEventListener('click', async function() {
        const supplierId = document.getElementById('supplier-id').value;
        const name = document.getElementById('supplier-name-input').value;
        const email = document.getElementById('supplier-email-input').value;
        const phone = document.getElementById('supplier-phone-input').value;
        const address = document.getElementById('supplier-address-input').value;
        
        // Validar nombre
        if (!name.trim()) {
            document.getElementById('supplier-name-input').classList.add('is-invalid');
            document.getElementById('supplier-name-error').textContent = 'El nombre es requerido';
            return;
        }
        
        // Deshabilitar botón mientras se guarda
        const saveBtn = document.getElementById('saveSupplierBtn');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        
        try {
            const response = await fetch(`/suppliers/${supplierId}/update-ajax`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name, email, phone, address })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Actualizar datos originales
                originalData = { name, email, phone, address };
                
                // Actualizar vista
                document.getElementById('supplier-name').textContent = name;
                document.getElementById('supplier-email').textContent = email || 'N/A';
                document.getElementById('supplier-phone').textContent = phone || 'N/A';
                document.getElementById('supplier-address').textContent = address || 'N/A';
                
                // Actualizar el enlace en la tabla
                document.querySelectorAll('.supplier-link').forEach(link => {
                    if (link.getAttribute('data-id') === supplierId) {
                        link.setAttribute('data-name', name);
                        link.setAttribute('data-email', email || 'N/A');
                        link.setAttribute('data-phone', phone || 'N/A');
                        link.setAttribute('data-address', address || 'N/A');
                        link.textContent = name;
                    }
                });
                
                alert('✓ Proveedor actualizado exitosamente');
                setViewMode();
            } else {
                throw new Error(data.message || 'Error al actualizar el proveedor');
            }
        } catch (error) {
            alert('Error: ' + error.message);
        } finally {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar';
        }
    });
    
    function setEditMode() {
        // Copiar valores a inputs
        document.getElementById('supplier-name-input').value = document.getElementById('supplier-name').textContent;
        document.getElementById('supplier-email-input').value = document.getElementById('supplier-email').textContent === 'N/A' ? '' : document.getElementById('supplier-email').textContent;
        document.getElementById('supplier-phone-input').value = document.getElementById('supplier-phone').textContent === 'N/A' ? '' : document.getElementById('supplier-phone').textContent;
        document.getElementById('supplier-address-input').value = document.getElementById('supplier-address').textContent === 'N/A' ? '' : document.getElementById('supplier-address').textContent;
        
        // Ocultar vista y mostrar edición
        document.querySelectorAll('.supplier-view').forEach(el => el.classList.add('d-none'));
        document.querySelectorAll('.supplier-edit').forEach(el => el.classList.remove('d-none'));
        document.querySelector('.supplier-view-buttons').classList.add('d-none');
        document.querySelector('.supplier-edit-buttons').classList.remove('d-none');
        
        // Limpiar errores
        document.getElementById('supplier-name-input').classList.remove('is-invalid');
        document.getElementById('supplier-name-error').textContent = '';
    }
    
    function setViewMode() {
        // Mostrar vista y ocultar edición
        document.querySelectorAll('.supplier-view').forEach(el => el.classList.remove('d-none'));
        document.querySelectorAll('.supplier-edit').forEach(el => el.classList.add('d-none'));
        document.querySelector('.supplier-view-buttons').classList.remove('d-none');
        document.querySelector('.supplier-edit-buttons').classList.add('d-none');
        
        // Limpiar errores
        document.getElementById('supplier-name-input').classList.remove('is-invalid');
        document.getElementById('supplier-name-error').textContent = '';
    }
</script>
@endpush

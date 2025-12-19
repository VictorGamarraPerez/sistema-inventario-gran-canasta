@extends('layouts.admin')

@section('title', 'Registrar Entrada')
@section('page-title', 'Registrar Entrada de Producto')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-arrow-down me-2 text-success"></i>
                    Registrar Ingreso de Producto
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('entries.store') }}" method="POST" id="entryForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">
                            <i class="fas fa-truck me-1"></i>
                            Proveedor <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-2">
                            <select class="form-select @error('supplier_id') is-invalid @enderror" 
                                    id="supplier_id" 
                                    name="supplier_id"
                                    required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" 
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createSupplierModal" title="Agregar nuevo proveedor">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Seleccione el proveedor que suministra el producto
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product_id" class="form-label">
                            <i class="fas fa-box me-1"></i>
                            Seleccionar Producto <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('product_id') is-invalid @enderror" 
                                id="product_id" 
                                name="product_id"
                                required>
                            <option value="">Seleccione un producto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-stock="{{ $product->stock }}"
                                        data-code="{{ $product->code }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->code }} - {{ $product->name }} (Stock actual: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <!-- Información del producto seleccionado -->
                        <div id="productInfo" class="mt-2" style="display: none;">
                            <div class="alert alert-info mb-0">
                                <strong>Stock actual:</strong> <span id="currentStock">0</span> unidades
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del documento -->
                    <div class="mb-3">
                        <label for="document_type" class="form-label">
                            <i class="fas fa-file-invoice me-1"></i>
                            Tipo de Doc.
                        </label>
                        <select class="form-select @error('document_type') is-invalid @enderror" 
                                id="document_type" 
                                name="document_type">
                            <option value="">Seleccione tipo de documento</option>
                            <option value="Boleta De Compra" {{ old('document_type') == 'Boleta De Compra' ? 'selected' : '' }}>Boleta De Compra</option>
                            <option value="Factura" {{ old('document_type') == 'Factura' ? 'selected' : '' }}>Factura</option>
                        </select>
                        @error('document_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="series" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>
                                Serie
                            </label>
                            <input type="text" 
                                   class="form-control @error('series') is-invalid @enderror" 
                                   id="series" 
                                   name="series" 
                                   value="{{ old('series') }}"
                                   placeholder="Ej: F001">
                            @error('series')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="number" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>
                                Numero
                            </label>
                            <input type="text" 
                                   class="form-control @error('number') is-invalid @enderror" 
                                   id="number" 
                                   name="number" 
                                   value="{{ old('number') }}"
                                   placeholder="Ej: 00012345">
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">
                                <i class="fas fa-sort-numeric-up me-1"></i>
                                Cantidad a Ingresar <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity', 1) }}"
                                   min="1"
                                   required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Cálculo de nuevo stock -->
                            <div id="newStockInfo" class="mt-2" style="display: none;">
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <strong>Nuevo stock:</strong> <span id="newStock">0</span> unidades
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="total" class="form-label">
                                <i class="fas fa-coins me-1"></i>
                                Total
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" 
                                       class="form-control @error('total') is-invalid @enderror" 
                                       id="total" 
                                       name="total" 
                                       value="{{ old('total') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00">
                                @error('total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="entry_date" class="form-label">
                                <i class="fas fa-calendar me-1"></i>
                                Fecha <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('entry_date') is-invalid @enderror" 
                                   id="entry_date" 
                                   name="entry_date" 
                                   value="{{ old('entry_date', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('entry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="observations" class="form-label">
                            <i class="fas fa-comment me-1"></i>
                            Observaciones
                        </label>
                        <textarea class="form-control @error('observations') is-invalid @enderror" 
                                  id="observations" 
                                  name="observations" 
                                  rows="3"
                                  placeholder="Ingrese cualquier observación o nota sobre esta entrada...">{{ old('observations') }}</textarea>
                        @error('observations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Resumen de la entrada -->
                    <div id="entrySummary" class="alert alert-light border" style="display: none;">
                        <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Resumen de la Entrada</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Producto:</strong> <span id="summaryProduct">-</span></p>
                                <p class="mb-1"><strong>Cantidad:</strong> <span id="summaryQuantity">0</span> unidades</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Stock actual:</strong> <span id="summaryCurrentStock">0</span></p>
                                <p class="mb-0"><strong class="text-success">Nuevo stock:</strong> <span id="summaryNewStock" class="text-success">0</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('entries.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Registrar Entrada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear nuevo proveedor -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSupplierModalLabel">
                    <i class="fas fa-truck me-2"></i>
                    Nuevo Proveedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="supplierForm">
                    @csrf
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label">
                            Nombre del Proveedor <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="supplier_name" 
                               name="name" 
                               required
                               placeholder="Ej: Distribuidora XYZ">
                        <div class="invalid-feedback" id="supplier_name_error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="supplier_email" class="form-label">
                            Email
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="supplier_email" 
                               name="email" 
                               placeholder="correo@ejemplo.com">
                    </div>
                    
                    <div class="mb-3">
                        <label for="supplier_phone" class="form-label">
                            Teléfono
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="supplier_phone" 
                               name="phone" 
                               placeholder="999 999 999">
                    </div>
                    
                    <div class="mb-3">
                        <label for="supplier_address" class="form-label">
                            Dirección
                        </label>
                        <textarea class="form-control" 
                                  id="supplier_address" 
                                  name="address" 
                                  rows="2"
                                  placeholder="Dirección del proveedor"></textarea>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>El proveedor se creará y estará disponible inmediatamente en el selector.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveSupplierBtn">
                    <i class="fas fa-save me-2"></i>Guardar Proveedor
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const productInfo = document.getElementById('productInfo');
    const currentStockSpan = document.getElementById('currentStock');
    const newStockInfo = document.getElementById('newStockInfo');
    const newStockSpan = document.getElementById('newStock');
    const entrySummary = document.getElementById('entrySummary');
    
    // Manejo del modal de proveedores
    const createSupplierModal = document.getElementById('createSupplierModal');
    const supplierForm = document.getElementById('supplierForm');
    const saveSupplierBtn = document.getElementById('saveSupplierBtn');
    const supplierNameInput = document.getElementById('supplier_name');
    const supplierNameError = document.getElementById('supplier_name_error');
    const supplierSelect = document.getElementById('supplier_id');
    
    // Guardar nuevo proveedor
    saveSupplierBtn.addEventListener('click', async function() {
        const formData = new FormData(supplierForm);
        
        // Limpiar errores previos
        supplierNameInput.classList.remove('is-invalid');
        supplierNameError.textContent = '';
        
        // Deshabilitar botón mientras se procesa
        saveSupplierBtn.disabled = true;
        saveSupplierBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        
        try {
            const response = await fetch('{{ route("suppliers.store.ajax") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Agregar el nuevo proveedor al selector
                const newOption = new Option(data.supplier.name, data.supplier.id, true, true);
                supplierSelect.add(newOption);
                
                // Mostrar mensaje de éxito
                alert('✓ Proveedor "' + data.supplier.name + '" creado exitosamente y seleccionado');
                
                // Limpiar formulario
                supplierForm.reset();
                
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(createSupplierModal);
                modal.hide();
            } else {
                throw new Error(data.message || 'Error al crear el proveedor');
            }
        } catch (error) {
            supplierNameInput.classList.add('is-invalid');
            supplierNameError.textContent = 'Este nombre de proveedor ya existe o es inválido';
        } finally {
            // Rehabilitar botón
            saveSupplierBtn.disabled = false;
            saveSupplierBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Proveedor';
        }
    });
    
    // Limpiar formulario al cerrar modal
    createSupplierModal.addEventListener('hidden.bs.modal', function() {
        supplierForm.reset();
        supplierNameInput.classList.remove('is-invalid');
        supplierNameError.textContent = '';
    });
    
    // Actualizar información del producto
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const currentStock = parseInt(selectedOption.dataset.stock);
            currentStockSpan.textContent = currentStock;
            productInfo.style.display = 'block';
            
            calculateNewStock();
            updateSummary();
        } else {
            productInfo.style.display = 'none';
            newStockInfo.style.display = 'none';
            entrySummary.style.display = 'none';
        }
    });
    
    // Calcular nuevo stock
    quantityInput.addEventListener('input', function() {
        calculateNewStock();
        updateSummary();
    });
    
    function calculateNewStock() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        
        if (productSelect.value && quantityInput.value) {
            const currentStock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(quantityInput.value);
            const newStock = currentStock + quantity;
            
            newStockSpan.textContent = newStock;
            newStockInfo.style.display = 'block';
        } else {
            newStockInfo.style.display = 'none';
        }
    }
    
    function updateSummary() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        
        if (productSelect.value && quantityInput.value) {
            const currentStock = parseInt(selectedOption.dataset.stock);
            const quantity = parseInt(quantityInput.value);
            const newStock = currentStock + quantity;
            const productName = selectedOption.text.split(' (Stock')[0];
            
            document.getElementById('summaryProduct').textContent = productName;
            document.getElementById('summaryQuantity').textContent = quantity;
            document.getElementById('summaryCurrentStock').textContent = currentStock;
            document.getElementById('summaryNewStock').textContent = newStock;
            
            entrySummary.style.display = 'block';
        } else {
            entrySummary.style.display = 'none';
        }
    }
    
    // Confirmación antes de enviar
    document.getElementById('entryForm').addEventListener('submit', function(e) {
        if (!confirm('¿Está seguro de registrar esta entrada? El stock del producto se actualizará automáticamente.')) {
            e.preventDefault();
        }
    });
</script>
@endpush

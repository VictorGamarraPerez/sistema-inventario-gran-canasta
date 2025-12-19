@extends('layouts.admin')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2 text-primary"></i>
                    Editar Producto: {{ $product->name }}
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">
                                Código del Producto <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code', $product->code) }}"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                Nombre del Producto <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">
                            Categoría <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex gap-2">
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id"
                                    required>
                                <option value="">Seleccione una categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCategoryModal" title="Agregar nueva categoría">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="purchase_price" class="form-label">
                                Precio de Compra <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" 
                                       class="form-control @error('purchase_price') is-invalid @enderror" 
                                       id="purchase_price" 
                                       name="purchase_price" 
                                       value="{{ old('purchase_price', $product->purchase_price) }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="sale_price" class="form-label">
                                Precio de Venta <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" 
                                       class="form-control @error('sale_price') is-invalid @enderror" 
                                       id="sale_price" 
                                       name="sale_price" 
                                       value="{{ old('sale_price', $product->sale_price) }}"
                                       step="0.01"
                                       min="0"
                                       required>
                                @error('sale_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="stock" class="form-label">
                                Stock Actual <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" 
                                   name="stock" 
                                   value="{{ old('stock', $product->stock) }}"
                                   min="0"
                                   required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="min_stock" class="form-label">Stock Mínimo</label>
                            <input type="number" 
                                   class="form-control @error('min_stock') is-invalid @enderror" 
                                   id="min_stock" 
                                   name="min_stock" 
                                   value="{{ old('min_stock', $product->min_stock) }}"
                                   min="0">
                            @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image" class="form-label">Imagen del Producto</label>
                        
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="img-thumbnail"
                                     style="max-width: 200px;">
                            </div>
                        @endif
                        
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image"
                               accept="image/*"
                               onchange="previewImage(event)">
                        <small class="text-muted">Deja vacío si no deseas cambiar la imagen</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear nueva categoría -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">
                    <i class="fas fa-folder-plus me-2"></i>
                    Nueva Categoría
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    @csrf
                    <div class="mb-3">
                        <label for="category_name" class="form-label">
                            Nombre de la Categoría <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="category_name" 
                               name="name" 
                               required
                               placeholder="Ej: Abarrotes, Limpieza, Bebidas">
                        <div class="invalid-feedback" id="category_name_error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_description" class="form-label">
                            Descripción
                        </label>
                        <textarea class="form-control" 
                                  id="category_description" 
                                  name="description" 
                                  rows="2"
                                  placeholder="Descripción opcional de la categoría"></textarea>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>La categoría se creará y estará disponible inmediatamente en el selector.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success" id="saveCategoryBtn">
                    <i class="fas fa-save me-2"></i>Guardar Categoría
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Manejo del modal de categorías
    const createCategoryModal = document.getElementById('createCategoryModal');
    const categoryForm = document.getElementById('categoryForm');
    const saveCategoryBtn = document.getElementById('saveCategoryBtn');
    const categoryNameInput = document.getElementById('category_name');
    const categoryNameError = document.getElementById('category_name_error');
    const categorySelect = document.getElementById('category_id');
    
    // Guardar nueva categoría
    saveCategoryBtn.addEventListener('click', async function() {
        const formData = new FormData(categoryForm);
        
        // Limpiar errores previos
        categoryNameInput.classList.remove('is-invalid');
        categoryNameError.textContent = '';
        
        // Deshabilitar botón mientras se procesa
        saveCategoryBtn.disabled = true;
        saveCategoryBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        
        try {
            const response = await fetch('{{ route("categories.store.ajax") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Agregar la nueva categoría al selector
                const newOption = new Option(data.category.name, data.category.id, true, true);
                categorySelect.add(newOption);
                
                // Mostrar mensaje de éxito
                alert('✓ Categoría "' + data.category.name + '" creada exitosamente y seleccionada');
                
                // Limpiar formulario
                categoryForm.reset();
                
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(createCategoryModal);
                modal.hide();
            } else {
                throw new Error(data.message || 'Error al crear la categoría');
            }
        } catch (error) {
            categoryNameInput.classList.add('is-invalid');
            categoryNameError.textContent = 'Este nombre de categoría ya existe o es inválido';
        } finally {
            // Rehabilitar botón
            saveCategoryBtn.disabled = false;
            saveCategoryBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Categoría';
        }
    });
    
    // Limpiar formulario al cerrar modal
    createCategoryModal.addEventListener('hidden.bs.modal', function() {
        categoryForm.reset();
        categoryNameInput.classList.remove('is-invalid');
        categoryNameError.textContent = '';
    });
    
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    }
</script>
@endpush

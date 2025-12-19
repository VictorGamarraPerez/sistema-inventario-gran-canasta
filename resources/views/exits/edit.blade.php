@extends('layouts.admin')

@section('page-title', 'Editar Salida')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Editar Salida de Producto</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('exits.update', $exit) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="product_id" class="form-label">Producto <span class="text-danger">*</span></label>
                            <select class="form-select @error('product_id') is-invalid @enderror" 
                                    id="product_id" name="product_id" required>
                                <option value="">Seleccionar producto...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-stock="{{ $product->stock }}"
                                            {{ old('product_id', $exit->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->code }} - {{ $product->name }} (Stock: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="stock-info" class="form-text"></div>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Cantidad <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity', $exit->quantity) }}" 
                                   min="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="quantity-warning" class="form-text text-danger" style="display: none;">
                                <i class="fas fa-exclamation-triangle"></i> La cantidad excede el stock disponible
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Motivo <span class="text-danger">*</span></label>
                            <select class="form-select @error('reason') is-invalid @enderror" 
                                    id="reason" name="reason" required>
                                <option value="">Seleccionar motivo...</option>
                                <option value="venta" {{ old('reason', $exit->reason) == 'venta' ? 'selected' : '' }}>Venta</option>
                                <option value="devolución" {{ old('reason', $exit->reason) == 'devolución' ? 'selected' : '' }}>Devolución</option>
                                <option value="pérdida" {{ old('reason', $exit->reason) == 'pérdida' ? 'selected' : '' }}>Pérdida</option>
                                <option value="merma" {{ old('reason', $exit->reason) == 'merma' ? 'selected' : '' }}>Merma</option>
                                <option value="donación" {{ old('reason', $exit->reason) == 'donación' ? 'selected' : '' }}>Donación</option>
                                <option value="otro" {{ old('reason', $exit->reason) == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exit_date" class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('exit_date') is-invalid @enderror" 
                                   id="exit_date" name="exit_date" 
                                   value="{{ old('exit_date', $exit->exit_date->format('Y-m-d')) }}" required>
                            @error('exit_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observations" class="form-label">Observaciones</label>
                            <textarea class="form-control @error('observations') is-invalid @enderror" 
                                      id="observations" name="observations" rows="3">{{ old('observations', $exit->observations) }}</textarea>
                            @error('observations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Advertencia sobre cambios -->
                        <div class="alert alert-warning border-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Nota:</strong> Al modificar esta salida, el stock del producto se ajustará automáticamente.
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('exits.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fas fa-save"></i> Actualizar Salida
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const stockInfo = document.getElementById('stock-info');
    const quantityWarning = document.getElementById('quantity-warning');
    const submitBtn = document.getElementById('submit-btn');

    function updateStockInfo() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');
        
        if (stock) {
            stockInfo.innerHTML = `<i class="fas fa-info-circle"></i> Stock disponible: <strong>${stock}</strong> unidades`;
            checkQuantity();
        } else {
            stockInfo.innerHTML = '';
        }
    }

    function checkQuantity() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        const quantity = parseInt(quantityInput.value);

        if (quantity && stock && quantity > stock) {
            quantityWarning.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            quantityWarning.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    productSelect.addEventListener('change', updateStockInfo);
    quantityInput.addEventListener('input', checkQuantity);

    // Initialize if product is preselected
    if (productSelect.value) {
        updateStockInfo();
    }
});
</script>
@endsection

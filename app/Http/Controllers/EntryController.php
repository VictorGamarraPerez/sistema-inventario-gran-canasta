<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Entry::with(['supplier', 'product', 'user']);

        // Búsqueda
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })->orWhereHas('supplier', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filtro por proveedor
        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filtro por fecha
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('entry_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('entry_date', '<=', $request->date_to);
        }

        $entries = $query->orderBy('entry_date', 'desc')->paginate(15);
        $suppliers = Supplier::where('active', true)->get();

        return view('entries.index', compact('entries', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::where('active', true)->get();
        $products = Product::where('active', true)->get();
        
        return view('entries.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'document_type' => 'nullable|string|in:Boleta De Compra,Factura',
            'series' => 'nullable|string|max:50',
            'number' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:1',
            'total' => 'nullable|numeric|min:0',
            'entry_date' => 'required|date',
            'observations' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Crear la entrada
            $validated['user_id'] = Auth::id();
            $entry = Entry::create($validated);

            // Actualizar el stock del producto
            $product = Product::findOrFail($validated['product_id']);
            $product->increment('stock', $validated['quantity']);

            DB::commit();

            return redirect()->route('entries.index')
                ->with('success', 'Entrada registrada exitosamente. Stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al registrar la entrada: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Entry $entry)
    {
        return view('entries.show', compact('entry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entry $entry)
    {
        $suppliers = Supplier::where('active', true)->get();
        $products = Product::where('active', true)->get();
        
        return view('entries.edit', compact('entry', 'suppliers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entry $entry)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id',
            'document_type' => 'nullable|string|in:Boleta De Compra,Factura',
            'series' => 'nullable|string|max:50',
            'number' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:1',
            'total' => 'nullable|numeric|min:0',
            'entry_date' => 'required|date',
            'observations' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Revertir el stock anterior
            $product = Product::findOrFail($entry->product_id);
            $product->decrement('stock', $entry->quantity);

            // Actualizar la entrada
            $entry->update($validated);

            // Aplicar el nuevo stock
            $newProduct = Product::findOrFail($validated['product_id']);
            $newProduct->increment('stock', $validated['quantity']);

            DB::commit();

            return redirect()->route('entries.index')
                ->with('success', 'Entrada actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la entrada: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entry $entry)
    {
        DB::beginTransaction();
        try {
            // Revertir el stock
            $product = Product::findOrFail($entry->product_id);
            $product->decrement('stock', $entry->quantity);

            // Eliminar la entrada
            $entry->delete();

            DB::commit();

            return redirect()->route('entries.index')
                ->with('success', 'Entrada eliminada exitosamente. Stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la entrada: ' . $e->getMessage());
        }
    }
}

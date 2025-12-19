<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductExit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductExit::with(['product', 'user']);

        // Búsqueda
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filtro por motivo
        if ($request->has('reason') && $request->reason != '') {
            $query->where('reason', $request->reason);
        }

        // Filtro por fecha
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('exit_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('exit_date', '<=', $request->date_to);
        }

        $exits = $query->orderBy('exit_date', 'desc')->paginate(15);

        return view('exits.index', compact('exits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('active', true)->where('stock', '>', 0)->get();
        
        return view('exits.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
            'exit_date' => 'required|date',
            'observations' => 'nullable|string'
        ]);

        // Verificar que hay suficiente stock
        $product = Product::findOrFail($validated['product_id']);
        
        if ($product->stock < $validated['quantity']) {
            return back()->withInput()
                ->with('error', 'No hay suficiente stock disponible. Stock actual: ' . $product->stock);
        }

        DB::beginTransaction();
        try {
            // Crear la salida
            $validated['user_id'] = Auth::id();
            $exit = ProductExit::create($validated);

            // Actualizar el stock del producto (restar)
            $product->decrement('stock', $validated['quantity']);

            DB::commit();

            return redirect()->route('exits.index')
                ->with('success', 'Salida registrada exitosamente. Stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al registrar la salida: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductExit $exit)
    {
        return view('exits.show', compact('exit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductExit $exit)
    {
        $products = Product::where('active', true)->get();
        
        return view('exits.edit', compact('exit', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductExit $exit)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
            'exit_date' => 'required|date',
            'observations' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Revertir el stock anterior
            $product = Product::findOrFail($exit->product_id);
            $product->increment('stock', $exit->quantity);

            // Verificar que hay suficiente stock para la nueva cantidad
            $newProduct = Product::findOrFail($validated['product_id']);
            if ($newProduct->stock < $validated['quantity']) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', 'No hay suficiente stock disponible. Stock actual: ' . $newProduct->stock);
            }

            // Actualizar la salida
            $exit->update($validated);

            // Aplicar el nuevo stock (restar)
            $newProduct->decrement('stock', $validated['quantity']);

            DB::commit();

            return redirect()->route('exits.index')
                ->with('success', 'Salida actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la salida: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductExit $exit)
    {
        DB::beginTransaction();
        try {
            // Revertir el stock (sumar lo que se había restado)
            $product = Product::findOrFail($exit->product_id);
            $product->increment('stock', $exit->quantity);

            // Eliminar la salida
            $exit->delete();

            DB::commit();

            return redirect()->route('exits.index')
                ->with('success', 'Salida eliminada exitosamente. Stock actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la salida: ' . $e->getMessage());
        }
    }
}

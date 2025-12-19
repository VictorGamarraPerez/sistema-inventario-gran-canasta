<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Store a newly created category via AJAX.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:255'
        ]);

        try {
            $category = Category::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la categoría: ' . $e->getMessage()
            ], 500);
        }
    }
}

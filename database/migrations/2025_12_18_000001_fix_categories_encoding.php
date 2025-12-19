<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Corregir los nombres de las categorías que tienen problemas de codificación
        $corrections = [
            'L?cteos' => 'Lácteos',
            'Lacteos' => 'Lácteos',
            'Panader?a' => 'Panadería',
            'Panaderia' => 'Panadería',
        ];

        foreach ($corrections as $wrong => $correct) {
            DB::table('categories')
                ->where('name', 'LIKE', $wrong)
                ->update(['name' => $correct]);
        }

        // Asegurar que la tabla use utf8mb4
        DB::statement('ALTER TABLE categories CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario revertir
    }
};

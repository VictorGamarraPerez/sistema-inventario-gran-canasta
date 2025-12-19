<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixCategoriesEncoding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:fix-encoding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige problemas de codificación en los nombres de las categorías';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Corrigiendo codificación de categorías...');

        // Obtener todas las categorías
        $categories = DB::table('categories')->get();

        $corrections = [
            'L?cteos' => 'Lácteos',
            'Lacteos' => 'Lácteos',
            'Panader?a' => 'Panadería',
            'Panaderia' => 'Panadería',
            'Beb?das' => 'Bebidas',
            'Verdur?s' => 'Verduras',
            'Energ?a' => 'Energía',
            'Higién?' => 'Higiene',
            'Higien?' => 'Higiene',
        ];

        $updated = 0;

        foreach ($categories as $category) {
            $originalName = $category->name;
            $correctedName = $originalName;

            // Buscar si necesita corrección
            foreach ($corrections as $wrong => $correct) {
                if (str_contains($originalName, $wrong) || $originalName === $wrong) {
                    $correctedName = str_replace($wrong, $correct, $originalName);
                    break;
                }
            }

            // Si el nombre cambió, actualizarlo
            if ($correctedName !== $originalName) {
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update(['name' => $correctedName]);
                
                $this->line("✓ '{$originalName}' → '{$correctedName}'");
                $updated++;
            }
        }

        if ($updated > 0) {
            $this->info("\n✓ Se corrigieron {$updated} categoría(s).");
        } else {
            $this->info("\n✓ No se encontraron categorías con problemas de codificación.");
        }

        // Mostrar categorías actuales
        $this->newLine();
        $this->info('Categorías actuales:');
        $currentCategories = DB::table('categories')->pluck('name');
        foreach ($currentCategories as $name) {
            $this->line("  - {$name}");
        }

        return Command::SUCCESS;
    }
}

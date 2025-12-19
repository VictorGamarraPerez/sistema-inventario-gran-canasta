<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\ProductExit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard with filters
     */
    public function index(Request $request)
    {
        // Filtros
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        $movementType = $request->input('movement_type', 'all'); // all, entry, exit
        $categoryId = $request->input('category_id', null);

        // Obtener datos para las gráficas y tabla
        $data = $this->getReportData($dateFrom, $dateTo, $movementType, $categoryId);

        $categories = Category::all();

        return view('reports.index', compact('data', 'categories', 'dateFrom', 'dateTo', 'movementType', 'categoryId'));
    }

    /**
     * Get report data based on filters
     */
    private function getReportData($dateFrom, $dateTo, $movementType, $categoryId)
    {
        $entries = collect();
        $exits = collect();

        // Obtener entradas si se solicita
        if (in_array($movementType, ['all', 'entry'])) {
            $entriesQuery = Entry::with(['product.category', 'user'])
                ->whereBetween('entry_date', [$dateFrom, $dateTo]);
            
            if ($categoryId) {
                $entriesQuery->whereHas('product', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            
            $entries = $entriesQuery->get()->map(function($entry) {
                return [
                    'date' => $entry->entry_date,
                    'type' => 'Entrada',
                    'product' => $entry->product->name,
                    'category' => $entry->product->category->name,
                    'quantity' => $entry->quantity,
                    'user' => $entry->user->name,
                    'observations' => $entry->observations,
                    'product_id' => $entry->product_id
                ];
            });
        }

        // Obtener salidas si se solicita
        if (in_array($movementType, ['all', 'exit'])) {
            $exitsQuery = ProductExit::with(['product.category', 'user'])
                ->whereBetween('exit_date', [$dateFrom, $dateTo]);
            
            if ($categoryId) {
                $exitsQuery->whereHas('product', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            
            $exits = $exitsQuery->get()->map(function($exit) {
                return [
                    'date' => $exit->exit_date,
                    'type' => 'Salida',
                    'product' => $exit->product->name,
                    'category' => $exit->product->category->name,
                    'quantity' => $exit->quantity,
                    'user' => $exit->user->name,
                    'observations' => $exit->observations,
                    'product_id' => $exit->product_id
                ];
            });
        }

        // Combinar y ordenar
        $movements = $entries->concat($exits)->sortByDesc('date')->values();

        // Estadísticas para gráficas
        $movementsByDay = $this->getMovementsByDay($dateFrom, $dateTo, $movementType, $categoryId);
        $topProducts = $this->getTopProducts($dateFrom, $dateTo, $movementType, $categoryId);
        $movementsByCategory = $this->getMovementsByCategory($dateFrom, $dateTo, $movementType, $categoryId);

        return [
            'movements' => $movements,
            'movementsByDay' => $movementsByDay,
            'topProducts' => $topProducts,
            'movementsByCategory' => $movementsByCategory,
            'totalEntries' => $entries->sum('quantity'),
            'totalExits' => $exits->sum('quantity'),
            'totalMovements' => $movements->count()
        ];
    }

    /**
     * Get movements grouped by day
     */
    private function getMovementsByDay($dateFrom, $dateTo, $movementType, $categoryId)
    {
        $days = [];
        $entries = [];
        $exits = [];

        $period = new \DatePeriod(
            new \DateTime($dateFrom),
            new \DateInterval('P1D'),
            (new \DateTime($dateTo))->modify('+1 day')
        );

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $days[] = $date->format('d/m');

            // Contar entradas
            if (in_array($movementType, ['all', 'entry'])) {
                $entryCount = Entry::whereDate('entry_date', $dateStr);
                if ($categoryId) {
                    $entryCount->whereHas('product', function($q) use ($categoryId) {
                        $q->where('category_id', $categoryId);
                    });
                }
                $entries[] = $entryCount->count();
            }

            // Contar salidas
            if (in_array($movementType, ['all', 'exit'])) {
                $exitCount = ProductExit::whereDate('exit_date', $dateStr);
                if ($categoryId) {
                    $exitCount->whereHas('product', function($q) use ($categoryId) {
                        $q->where('category_id', $categoryId);
                    });
                }
                $exits[] = $exitCount->count();
            }
        }

        return [
            'labels' => $days,
            'entries' => $entries,
            'exits' => $exits
        ];
    }

    /**
     * Get top 5 most moved products
     */
    private function getTopProducts($dateFrom, $dateTo, $movementType, $categoryId)
    {
        $products = [];

        if (in_array($movementType, ['all', 'entry'])) {
            $entryProducts = Entry::with('product')
                ->whereBetween('entry_date', [$dateFrom, $dateTo])
                ->when($categoryId, function($q) use ($categoryId) {
                    $q->whereHas('product', function($query) use ($categoryId) {
                        $query->where('category_id', $categoryId);
                    });
                })
                ->select('product_id', DB::raw('SUM(quantity) as total'))
                ->groupBy('product_id')
                ->get();

            foreach ($entryProducts as $item) {
                $productName = $item->product->name;
                if (!isset($products[$productName])) {
                    $products[$productName] = 0;
                }
                $products[$productName] += $item->total;
            }
        }

        if (in_array($movementType, ['all', 'exit'])) {
            $exitProducts = ProductExit::with('product')
                ->whereBetween('exit_date', [$dateFrom, $dateTo])
                ->when($categoryId, function($q) use ($categoryId) {
                    $q->whereHas('product', function($query) use ($categoryId) {
                        $query->where('category_id', $categoryId);
                    });
                })
                ->select('product_id', DB::raw('SUM(quantity) as total'))
                ->groupBy('product_id')
                ->get();

            foreach ($exitProducts as $item) {
                $productName = $item->product->name;
                if (!isset($products[$productName])) {
                    $products[$productName] = 0;
                }
                $products[$productName] += $item->total;
            }
        }

        arsort($products);
        $topProducts = array_slice($products, 0, 5, true);

        return [
            'labels' => array_keys($topProducts),
            'data' => array_values($topProducts)
        ];
    }

    /**
     * Get movements by category
     */
    private function getMovementsByCategory($dateFrom, $dateTo, $movementType, $categoryId)
    {
        $categories = [];

        if (in_array($movementType, ['all', 'entry'])) {
            $entryCategories = Entry::with('product.category')
                ->whereBetween('entry_date', [$dateFrom, $dateTo])
                ->when($categoryId, function($q) use ($categoryId) {
                    $q->whereHas('product', function($query) use ($categoryId) {
                        $query->where('category_id', $categoryId);
                    });
                })
                ->get()
                ->groupBy('product.category.name');

            foreach ($entryCategories as $categoryName => $items) {
                if (!isset($categories[$categoryName])) {
                    $categories[$categoryName] = 0;
                }
                $categories[$categoryName] += $items->sum('quantity');
            }
        }

        if (in_array($movementType, ['all', 'exit'])) {
            $exitCategories = ProductExit::with('product.category')
                ->whereBetween('exit_date', [$dateFrom, $dateTo])
                ->when($categoryId, function($q) use ($categoryId) {
                    $q->whereHas('product', function($query) use ($categoryId) {
                        $query->where('category_id', $categoryId);
                    });
                })
                ->get()
                ->groupBy('product.category.name');

            foreach ($exitCategories as $categoryName => $items) {
                if (!isset($categories[$categoryName])) {
                    $categories[$categoryName] = 0;
                }
                $categories[$categoryName] += $items->sum('quantity');
            }
        }

        arsort($categories);

        return [
            'labels' => array_keys($categories),
            'data' => array_values($categories)
        ];
    }

    /**
     * Export report to Excel
     */
    public function exportExcel(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        $movementType = $request->input('movement_type', 'all');
        $categoryId = $request->input('category_id', null);

        $data = $this->getReportData($dateFrom, $dateTo, $movementType, $categoryId);

        return Excel::download(
            new ReportsExport($data['movements']), 
            'reporte_inventario_' . date('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Export report to PDF
     */
    public function exportPdf(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        $movementType = $request->input('movement_type', 'all');
        $categoryId = $request->input('category_id', null);

        $data = $this->getReportData($dateFrom, $dateTo, $movementType, $categoryId);
        
        $category = $categoryId ? Category::find($categoryId) : null;

        $pdf = Pdf::loadView('reports.pdf', [
            'movements' => $data['movements'],
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'movementType' => $movementType,
            'category' => $category,
            'totalEntries' => $data['totalEntries'],
            'totalExits' => $data['totalExits'],
            'totalMovements' => $data['totalMovements']
        ]);

        return $pdf->download('reporte_inventario_' . date('Y-m-d_His') . '.pdf');
    }
}

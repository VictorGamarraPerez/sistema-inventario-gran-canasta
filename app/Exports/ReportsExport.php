<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $movements;

    public function __construct($movements)
    {
        $this->movements = $movements;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->movements->map(function($movement, $index) {
            return [
                'N°' => $index + 1,
                'Fecha' => \Carbon\Carbon::parse($movement['date'])->format('d/m/Y'),
                'Tipo' => $movement['type'],
                'Producto' => $movement['product'],
                'Categoría' => $movement['category'],
                'Cantidad' => $movement['quantity'],
                'Usuario' => $movement['user'],
                'Observaciones' => $movement['observations'] ?? '-'
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'N°',
            'Fecha',
            'Tipo',
            'Producto',
            'Categoría',
            'Cantidad',
            'Usuario',
            'Observaciones'
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '667eea']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 12,
            'C' => 12,
            'D' => 30,
            'E' => 15,
            'F' => 10,
            'G' => 20,
            'H' => 40,
        ];
    }
}

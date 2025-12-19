<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #667eea;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-section table {
            width: 100%;
        }
        .info-section td {
            padding: 5px;
        }
        .info-section td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }
        .summary-card {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            flex: 1;
            margin: 0 10px;
        }
        .summary-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }
        .summary-card .label {
            color: #666;
            font-size: 12px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.data-table thead {
            background: #667eea;
            color: white;
        }
        table.data-table th,
        table.data-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table.data-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }
        .badge-entrada {
            background: #28a745;
        }
        .badge-salida {
            background: #dc3545;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LA GRAN CANASTA</h1>
        <p>Reporte de Movimientos de Inventario</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td>Período:</td>
                <td>{{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Tipo de Movimiento:</td>
                <td>
                    @if($movementType == 'all')
                        Todos (Entradas y Salidas)
                    @elseif($movementType == 'entry')
                        Solo Entradas
                    @else
                        Solo Salidas
                    @endif
                </td>
            </tr>
            @if($category)
            <tr>
                <td>Categoría:</td>
                <td>{{ $category->name }}</td>
            </tr>
            @endif
            <tr>
                <td>Fecha de Generación:</td>
                <td>{{ now()->format('d/m/Y H:i:s') }}</td>
            </tr>
        </table>
    </div>

    <div class="summary" style="display: table; width: 100%; margin-bottom: 30px;">
        <div class="summary-card" style="display: table-cell; width: 33.33%; padding: 15px; background: #f8f9fa; text-align: center;">
            <div class="label">Total Entradas</div>
            <div class="value">{{ number_format($totalEntries) }}</div>
        </div>
        <div class="summary-card" style="display: table-cell; width: 33.33%; padding: 15px; background: #f8f9fa; text-align: center;">
            <div class="label">Total Salidas</div>
            <div class="value">{{ number_format($totalExits) }}</div>
        </div>
        <div class="summary-card" style="display: table-cell; width: 33.33%; padding: 15px; background: #f8f9fa; text-align: center;">
            <div class="label">Total Movimientos</div>
            <div class="value">{{ number_format($totalMovements) }}</div>
        </div>
    </div>

    <h3 style="color: #667eea; margin-top: 30px;">Detalle de Movimientos</h3>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">Fecha</th>
                <th style="width: 12%;">Movimiento</th>
                <th style="width: 25%;">Producto</th>
                <th style="width: 15%;">Categoría</th>
                <th style="width: 10%;">Cantidad</th>
                <th style="width: 15%;">Usuario</th>
                <th style="width: 13%;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movements as $movement)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($movement['date'])->format('d/m/Y') }}</td>
                    <td>
                        @if($movement['type'] == 'Entrada')
                            <span class="badge badge-entrada">ENTRADA</span>
                        @else
                            <span class="badge badge-salida">SALIDA</span>
                        @endif
                    </td>
                    <td>{{ $movement['product'] }}</td>
                    <td>{{ $movement['category'] }}</td>
                    <td><strong>{{ $movement['quantity'] }}</strong></td>
                    <td>{{ $movement['user'] }}</td>
                    <td>{{ $movement['observations'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #999;">
                        No se encontraron movimientos en el período seleccionado
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Control de Inventario - LA GRAN CANASTA</p>
        <p>Este documento fue generado automáticamente el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }}</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Alquiler</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    <h2>Recibo de Alquiler</h2>
    <p><strong>Cliente:</strong> {{ $record->cliente->name }}</p>
    <p><strong>CI:</strong> {{ $record->cliente->ci }}</p>
    <p><strong>Fecha de alquiler:</strong> {{ $record->fecha_alquiler }}</p>
    <p><strong>Fecha de devolución:</strong> {{ $record->fecha_devolucion }}</p>

    <table>
        <thead>
            <tr>
                <th>Disfraz</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($record->alquilerDisfrazs as $item)
                <tr>
                    <td>{{ $item->disfraz->nombre }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>Bs {{ number_format($item->precio_alquiler, 2) }}</td>
                    <td>Bs {{ number_format($item->cantidad * $item->precio_alquiler, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @php
    $total = $record->alquilerDisfrazs->sum(function($item) {
        return $item->cantidad * $item->precio_alquiler;
    });
    @endphp
    <p><strong>SubTotal:</strong> Bs {{ number_format($total, 2) }}</p>
    <p><strong>Tipo de Garantía:</strong> {{ ucfirst($record->tipo_garantia) }}</p>
    <p><strong>Valor de Garantía:</strong> Bs {{ number_format($record->valor_garantia, 2) }}</p>
    <p><strong>Total:</strong> Bs {{ number_format($total + $record->valor_garantia, 2) }}</p>
</body>
</html>

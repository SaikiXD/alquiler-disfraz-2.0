<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Alquileres</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Reporte de Alquileres</h2>
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Fecha Alquiler</th>
                <th>Fecha Devolución</th>
                <th>Monto</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registros as $item)
                <tr>
                    <td>{{ $item->cliente->name }}</td>
                    <td>{{ $item->fecha_alquiler }}</td>
                    <td>{{ $item->fecha_devolucion }}</td>
                    <td>{{ $item->total }} BOB</td>
                    <td>{{ $item->status->name ?? $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
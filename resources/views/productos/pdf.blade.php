<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Catálogo Zapadictos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; font-size: 12px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Catálogo Zapadictos</h2>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach($productos as $p)
        <tr>
            <td>{{ $p->nombre }}</td>
            <td>{{ $p->marca }}</td>
            <td>{{ $p->categoria }}</td>
            <td>{{ number_format((float) $p->precio, 2, ',', '.') }}</td>
            <td>{{ $p->stock }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

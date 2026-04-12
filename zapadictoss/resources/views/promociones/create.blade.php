<h1>Nueva Promoción</h1>

<form action="{{ route('promociones.store') }}" method="POST">
    @csrf

    <label>Producto:</label><br>
    <select name="producto_id" required>
        @foreach($productos as $prod)
            <option value="{{ $prod->id }}">{{ $prod->nombre }}</option>
        @endforeach
    </select>
    <br><br>

    <label>Descuento (%):</label><br>
    <input type="number" name="descuento" required>
    <br><br>

    <label>Fecha inicio:</label><br>
    <input type="date" name="fecha_inicio" required>
    <br><br>

    <label>Fecha fin:</label><br>
    <input type="date" name="fecha_fin" required>
    <br><br>

    <button type="submit">Guardar</button>
</form>

<br>

<a href="{{ route('promociones.index') }}">Volver</a>
``
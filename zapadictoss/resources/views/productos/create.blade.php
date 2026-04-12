<h1>Nuevo Producto</h1>

<form action="{{ route('productos.store') }}" method="POST">
    @csrf

    <input name="nombre" placeholder="Nombre"><br><br>
    <input name="marca" placeholder="Marca"><br><br>
    <input name="categoria" placeholder="Categoría"><br><br>
    <input name="precio" placeholder="Precio"><br><br>
    <input name="stock" placeholder="Stock"><br><br>

    <button type="submit">Guardar</button>
</form>

<a href="{{ route('productos.index') }}">Volver</a>
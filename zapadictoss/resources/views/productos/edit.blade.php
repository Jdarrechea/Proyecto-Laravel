<h1>Editar Producto</h1>

<form action="{{ route('productos.update', $producto) }}" method="POST">
    @csrf
    @method('PUT')

    <input name="nombre" value="{{ $producto->nombre }}"><br><br>
    <input name="marca" value="{{ $producto->marca }}"><br><br>
    <input name="categoria" value="{{ $producto->categoria }}"><br><br>
    <input name="precio" value="{{ $producto->precio }}"><br><br>
    <input name="stock" value="{{ $producto->stock }}"><br><br>

    <button type="submit">Actualizar</button>
</form>

<a href="{{ route('productos.index') }}">Volver</a>
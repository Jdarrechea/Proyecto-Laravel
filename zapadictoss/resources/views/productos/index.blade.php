<h1>Zapadictos - Productos</h1>

<form method="GET">
    <input name="q" value="{{ $q }}" placeholder="Buscar producto">
    <button type="submit">Buscar</button>
</form>

<p>
    <a href="{{ route('productos.create') }}">➕ Nuevo producto</a>
    <a href="{{ route('productos.pdf') }}">📄 Descargar PDF</a>
</p>


<table border="1" cellpadding="6">
    <tr>
        <th>Nombre</th>
        <th>Marca</th>
        <th>Precio</th>
        <th>Acciones</th>
    </tr>

    @foreach($productos as $p)
    <tr>
        <td>{{ $p->nombre }}</td>
        <td>{{ $p->marca }}</td>
        <td>{{ $p->precio }}</td>
        <td>
            <a href="{{ route('productos.edit', $p) }}">Editar</a>

            <form action="{{ route('productos.destroy', $p) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{{ $productos->links() }}
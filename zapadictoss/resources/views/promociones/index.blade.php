<h1>Promociones</h1>

<p>
    <a href="{{ route('promociones.create') }}">➕ Nueva promoción</a>
</p>


<table border="1" cellpadding="6">
    <tr>
        <th>Producto</th>
        <th>Descuento</th>
        <th>Acción</th>
    </tr>

    @foreach($promociones as $p)
    <tr>
    <td>{{ $p->producto->nombre }}</td>
    <td>{{ $p->descuento }}%</td>
    <td>
        <form action="{{ route('promociones.destroy', $p) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Eliminar</button>
        </form>
    </td>
</tr>
    @endforeach
</table>

{{ $promociones->links() }}

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
            {{ route('promociones.destroy', $p) }}
                @csrf
                @method('DELETE')
                <button>Eliminar</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{{ $promociones->links() }}

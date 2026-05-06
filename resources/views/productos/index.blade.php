@extends('layouts.app')

@section('title', 'Productos - Zapadictos')
@section('page-title', 'Productos disponibles')
@section('page-description', 'Consulta, crea y administra el inventario de tus calzados favoritos.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2>Inventario</h2>
            <p class="page-description">Lista de productos cargados en la tienda.</p>
        </div>
        <div class="actions">
            <a href="{{ route('productos.pdf') }}" class="btn-secondary">Descargar catálogo</a>
            <a href="{{ route('productos.create') }}" class="btn">Agregar producto</a>
        </div>
    </div>

    <form method="GET" class="card" style="margin-bottom: 1rem;">
        <div class="form-grid" style="grid-template-columns: 1fr auto; gap: 0.75rem; align-items: end;">
            <label style="display:none;" for="q">Buscar</label>
            <input id="q" name="q" value="{{ $q }}" placeholder="Buscar por nombre o marca">
            <button type="submit" class="btn">Buscar</button>
        </div>
    </form>

    @if($productos->count())
    <div class="table-wrapper">
        <table class="table-products">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $p)
                <tr>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->marca }}</td>
                    <td>{{ $p->categoria }}</td>
                    <td>${{ number_format((float) $p->precio, 2, ',', '.') }}</td>
                    <td>{{ $p->stock }}</td>
                    <td>
                        <a href="{{ route('productos.edit', $p) }}" class="btn-secondary">Editar</a>
                        <form action="{{ route('productos.destroy', $p) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $productos->links() }}
    </div>
    @else
    <div class="card">
        <p>No hay productos registrados aún. Crea uno para comenzar.</p>
    </div>
    @endif
</div>
@endsection
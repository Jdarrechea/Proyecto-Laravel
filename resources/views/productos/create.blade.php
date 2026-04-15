@extends('layouts.app')

@section('title', 'Crear Producto - Zapadictos')
@section('page-title', 'Nuevo producto')
@section('page-description', 'Agrega un nuevo modelo de calzado al inventario con facilidad.')

@section('content')
<div class="card">
    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data" class="form-grid">
        @csrf

        <div>
            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" placeholder="Nombre" required>
        </div>

        <div>
            <label for="marca">Marca</label>
            <input id="marca" name="marca" placeholder="Marca" required>
        </div>

        <div>
            <label for="categoria">Categoría</label>
            <input id="categoria" name="categoria" placeholder="Categoría" required>
        </div>

        <div>
            <label for="precio">Precio</label>
            <input id="precio" name="precio" type="number" step="0.01" min="0" placeholder="Precio" required>
        </div>

        <div>
            <label for="stock">Stock</label>
            <input id="stock" name="stock" placeholder="Stock" required>
        </div>

        <div>
            <label for="imagen">Imagen del producto</label>
            <input id="imagen" name="imagen" type="file" accept="image/*" required>
        </div>

        <div>
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Descripción opcional del producto"></textarea>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn">Guardar producto</button>
            <a href="{{ route('productos.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
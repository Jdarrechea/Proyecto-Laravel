@extends('layouts.app')

@section('title', 'Editar Producto - Zapadictos')
@section('page-title', 'Editar producto')
@section('page-description', 'Modifica los detalles del producto en el inventario.')

@section('content')
<div class="card">
    <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data" class="form-grid">
        @csrf
        @method('PUT')

        <div>
            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" value="{{ $producto->nombre }}" required>
        </div>

        <div>
            <label for="marca">Marca</label>
            <input id="marca" name="marca" value="{{ $producto->marca }}" required>
        </div>

        <div>
            <label for="categoria">Categoría</label>
            <input id="categoria" name="categoria" value="{{ $producto->categoria }}" required>
        </div>

        <div>
            <label for="precio">Precio</label>
            <input id="precio" name="precio" value="{{ $producto->precio }}" required>
        </div>

        <div>
            <label for="stock">Stock</label>
            <input id="stock" name="stock" value="{{ $producto->stock }}" required>
        </div>

        <div>
            <label for="imagen">Imagen del producto</label>
            <input id="imagen" name="imagen" type="file" accept="image/*">
        </div>

        <div>
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Descripción opcional del producto">{{ $producto->descripcion }}</textarea>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn">Actualizar</button>
            <a href="{{ route('productos.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
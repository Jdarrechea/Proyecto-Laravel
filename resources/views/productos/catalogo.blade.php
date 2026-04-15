@extends('layouts.app')

@section('title', 'Catálogo de zapatillas - Zapadictos')
@section('page-title', 'Catálogo de zapatillas')
@section('page-description', 'Descubre los modelos disponibles con imágenes y detalles de cada par.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2>Catálogo visual</h2>
            <p class="page-description">Explora las zapatillas .</p>
        </div>
        <div class="actions">
            <a href="{{ route('productos.create') }}" class="btn">Agregar producto</a>
        </div>
    </div>

    @if($productos->count())
    <div class="product-grid">
        @foreach($productos as $producto)
        @php
            $imagen = $producto->imagen ? (str_starts_with($producto->imagen, 'http') ? $producto->imagen : asset('storage/' . $producto->imagen)) : 'https://via.placeholder.com/520x360?text=Zapatillas+Zapadictos';
            $promocion = $producto->promocionActiva;
            $precioOriginal = (float) $producto->precio;
            $precioFinal = $promocion ? $producto->precio_con_descuento : $precioOriginal;
        @endphp
        <article class="product-card">
            <img src="{{ $imagen }}" alt="Imagen de {{ $producto->nombre }}">
            <div class="product-info">
                <span class="product-tag">{{ $producto->categoria }}</span>
                <h3>{{ $producto->nombre }}</h3>
                <p class="product-subtitle">{{ $producto->marca }}</p>
                <p class="product-description">{{ $producto->descripcion ?: 'No hay descripción disponible.' }}</p>
                @if($promocion)
                <div class="product-badge">Oferta {{ $promocion->descuento }}% hasta {{ $promocion->fecha_fin->format('d/m/Y') }}</div>
                @endif
                <div class="product-meta">
                    @if($promocion)
                    <span class="product-price discount">${{ number_format($precioFinal, 2, ',', '.') }}</span>
                    <span class="product-original">${{ number_format($precioOriginal, 2, ',', '.') }}</span>
                    @else
                    <span>${{ number_format($precioOriginal, 2, ',', '.') }}</span>
                    @endif
                    <span>Stock: {{ $producto->stock }}</span>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    @else
    <div class="card">
        <p>No hay productos aún. Agrega zapatillas para verlas en el catálogo.</p>
    </div>
    @endif
</div>
@endsection
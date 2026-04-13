@extends('layouts.app')

@section('title', 'Crear Promoción - Zapadictos')
@section('page-title', 'Nueva promoción')
@section('page-description', 'Agrega un descuento a un producto existente y controla sus fechas de vigencia.')

@section('content')
<div class="card">
    <form action="{{ route('promociones.store') }}" method="POST" class="form-grid">
        @csrf

        <div>
            <label for="producto_id">Producto</label>
            <select id="producto_id" name="producto_id" required>
                @foreach($productos as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="descuento">Descuento (%)</label>
            <input id="descuento" name="descuento" type="number" min="0" max="100" placeholder="Ej. 15" required>
        </div>

        <div>
            <label for="fecha_inicio">Fecha inicio</label>
            <input id="fecha_inicio" type="date" name="fecha_inicio" required>
        </div>

        <div>
            <label for="fecha_fin">Fecha fin</label>
            <input id="fecha_fin" type="date" name="fecha_fin" required>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn">Guardar promoción</button>
            <a href="{{ route('promociones.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
``
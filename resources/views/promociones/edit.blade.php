@extends('layouts.app')

@section('title', 'Editar Promoción - Zapadictos')
@section('page-title', 'Editar promoción')
@section('page-description', 'Ajusta el descuento y las fechas de vigencia del producto en promoción.')

@section('content')
<div class="card">
    <form action="{{ route('promociones.update', $promocion) }}" method="POST" class="form-grid">
        @csrf
        @method('PUT')

        <div>
            <label for="producto_id">Producto</label>
            <select id="producto_id" name="producto_id" required>
                @foreach($productos as $prod)
                    <option value="{{ $prod->id }}" {{ $prod->id === $promocion->producto_id ? 'selected' : '' }}>{{ $prod->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="descuento">Descuento (%)</label>
            <input id="descuento" name="descuento" type="number" min="0" max="100" value="{{ $promocion->descuento }}" required>
        </div>

        <div>
            <label for="fecha_inicio">Fecha inicio</label>
            <input id="fecha_inicio" type="date" name="fecha_inicio" value="{{ $promocion->fecha_inicio->format('Y-m-d') }}" required>
        </div>

        <div>
            <label for="fecha_fin">Fecha fin</label>
            <input id="fecha_fin" type="date" name="fecha_fin" value="{{ $promocion->fecha_fin->format('Y-m-d') }}" required>
        </div>

        <label class="checkbox-label">
            <input type="checkbox" name="activa" value="1" {{ $promocion->activa ? 'checked' : '' }}>
            Activa
        </label>

        <div class="form-footer">
            <button type="submit" class="btn">Actualizar promoción</button>
            <a href="{{ route('promociones.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection

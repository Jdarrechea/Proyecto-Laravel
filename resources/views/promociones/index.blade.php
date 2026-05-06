@extends('layouts.app')

@section('title', 'Promociones - Zapadictos')
@section('page-title', 'Promociones activas')
@section('page-description', 'Administra descuentos y promociones para tus productos de calzado favoritos.')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2>Promociones</h2>
            <p class="page-description">Gestiona las ofertas y descuentos vigentes.</p>
        </div>
        <div class="actions">
            <a href="{{ route('promociones.create') }}" class="btn">Nueva promoción</a>
        </div>
    </div>

    @if($promociones->count())
    <div class="table-wrapper">
        <table class="table-products">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Descuento</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promociones as $p)
                <tr>
                    <td>{{ $p->producto->nombre }}</td>
                    <td>{{ $p->descuento }}%</td>
                    <td>{{ $p->fecha_inicio->format('d/m/Y') }}</td>
                    <td>{{ $p->fecha_fin->format('d/m/Y') }}</td>
                    <td>{{ $p->activa ? 'Activa' : 'Finalizada' }}</td>
                    <td>
                        <a href="{{ route('promociones.edit', $p) }}" class="btn-secondary">Editar</a>
                        <form action="{{ route('promociones.destroy', $p) }}" method="POST" style="display:inline">
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
        {{ $promociones->links() }}
    </div>
    @else
    <div class="card">
        <p>No hay promociones registradas. Crea una para comenzar.</p>
    </div>
    @endif
</div>
@endsection

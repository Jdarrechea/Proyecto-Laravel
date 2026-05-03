@extends('layouts.app')

@section('title', 'Venta confirmada - Zapadictos')
@section('page-title', 'Venta registrada')
@section('page-description', 'El pago fue procesado y la venta quedo registrada formalmente en el sistema.')

@section('content')
<section class="card">
    <div class="card-header">
        <div>
            <h2>Resumen de venta #{{ $venta->id }}</h2>
            <p class="page-description">Cliente: {{ $venta->nombre_envio ?: $venta->usuario->name }}</p>
            <p class="page-description">Referencia de pago: {{ $venta->referencia_pago }}</p>
            <p class="page-description">Metodo de pago: {{ ucfirst($venta->metodo_pago) }}</p>
            <p class="page-description">
                Envio: {{ $venta->nombre_envio }} - {{ $venta->pais_envio }}, {{ $venta->ciudad_envio }}, {{ $venta->direccion_envio }}
            </p>
        </div>
        <strong class="sale-total">${{ number_format($venta->total, 2, ',', '.') }}</strong>
    </div>

    <div class="table-wrapper">
        <table class="table-products">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                        <td>${{ number_format($detalle->subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="form-footer">
        <a href="{{ route('productos.catalogo') }}" class="btn">Volver al catalogo</a>
    </div>
</section>
@endsection

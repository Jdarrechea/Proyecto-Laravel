@extends('layouts.app')

@section('title', 'Pedidos - Zapadictos')
@section('page-title', 'Pedidos de clientes')
@section('page-description', 'Revisa las compras realizadas por usuarios normales y consulta el detalle de cada pedido.')

@section('content')
<section class="catalog-dashboard">
    <div class="dashboard-stat">
        <span>Pedidos registrados</span>
        <strong>{{ $cantidadPedidos }}</strong>
    </div>
    <div class="dashboard-stat">
        <span>Productos vendidos</span>
        <strong>{{ $productosVendidos }}</strong>
    </div>
    <div class="dashboard-stat">
        <span>Total vendido</span>
        <strong>${{ number_format($totalVentas, 2, ',', '.') }}</strong>
    </div>
</section>

<section class="card">
    <div class="card-header">
        <div>
            <h2>Historial de pedidos</h2>
            <p class="page-description">Pedidos finalizados desde el carrito de compras.</p>
        </div>
        @if($ventas->count())
            <form method="POST" action="{{ route('ventas.destroy-history') }}" onsubmit="return confirm('Seguro que deseas eliminar todo el historial de pedidos?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Eliminar historial</button>
            </form>
        @endif
    </div>

    @if($ventas->count())
        <div class="orders-list">
            @foreach($ventas as $venta)
                <article class="order-card">
                    <div class="order-summary">
                        <div>
                            <span class="product-tag">Pedido #{{ $venta->id }}</span>
                            <h3>{{ $venta->nombre_envio ?: $venta->usuario->name }}</h3>
                            <p>{{ $venta->usuario->email }}</p>
                        </div>
                        <div class="order-total">
                            <strong>${{ number_format($venta->total, 2, ',', '.') }}</strong>
                            <span>{{ ucfirst($venta->estado_pago) }}</span>
                        </div>
                    </div>

                    <div class="order-meta">
                        <span>Referencia: {{ $venta->referencia_pago }}</span>
                        <span>Pago: {{ ucfirst($venta->metodo_pago) }}</span>
                        <span>Envio: {{ $venta->ciudad_envio }}, {{ $venta->direccion_envio }}</span>
                        <span>Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="table-wrapper">
                        <table class="table-products orders-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
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
                        <a href="{{ route('ventas.show', $venta) }}" class="btn-secondary">Ver comprobante</a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination">
            {{ $ventas->links() }}
        </div>
    @else
        <div class="empty-state">
            <p>Aun no hay pedidos registrados.</p>
        </div>
    @endif
</section>
@endsection

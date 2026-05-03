<div>
    <section class="catalog-dashboard">
        <div class="dashboard-stat">
            <span>En carrito</span>
            <strong>{{ $this->cantidadProductos }}</strong>
        </div>
        <div class="dashboard-stat">
            <span>Total estimado</span>
            <strong>${{ number_format($this->total, 2, ',', '.') }}</strong>
        </div>
    </section>

    @if(auth()->user()->role === 'normal')
        <section class="cart-panel cart-panel-inline">
            <div class="cart-header">
                <div>
                    <h2>Carrito de compras</h2>
                    <p>{{ $this->cantidadProductos }} productos seleccionados</p>
                </div>
                <div class="cart-summary-actions">
                    <strong>${{ number_format($this->total, 2, ',', '.') }}</strong>
                    <button type="button" class="btn-secondary" wire:click="alternarCarrito">
                        {{ $carritoAbierto ? 'Ocultar carrito' : 'Ver carrito' }}
                    </button>
                </div>
            </div>

            @if($carritoAbierto)
                @if(count($carrito))
                    <div class="cart-list">
                        @foreach($carrito as $item)
                            <article class="cart-item">
                                <img src="{{ $item['imagen'] }}" alt="Imagen de {{ $item['nombre'] }}">
                                <div>
                                    <h3>{{ $item['nombre'] }}</h3>
                                    <p>{{ $item['marca'] }}</p>
                                    <span>Subtotal: ${{ number_format($item['precio'] * $item['cantidad'], 2, ',', '.') }}</span>
                                    <div class="quantity-controls">
                                        <button type="button" wire:click="disminuir({{ $item['id'] }})">-</button>
                                        <strong>{{ $item['cantidad'] }}</strong>
                                        <button type="button" wire:click="incrementar({{ $item['id'] }})">+</button>
                                        <button type="button" wire:click="quitarProducto({{ $item['id'] }})">Quitar</button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="cart-footer-actions">
                        <button type="button" class="btn-secondary cart-clear" wire:click="vaciarCarrito">
                            Vaciar carrito
                        </button>

                        <button type="button" class="btn checkout-button" wire:click="mostrarPago">
                            Finalizar compra
                        </button>
                    </div>

                    @if($mostrarMetodosPago)
                        <div class="payment-panel">
                            <div>
                                <h3>Datos de envio y pago</h3>
                                <p>Completa la informacion de entrega y selecciona como quieres pagar tu pedido.</p>
                            </div>

                            <form method="POST" action="{{ route('ventas.store') }}" class="checkout-details-form">
                                @csrf

                                <div class="shipping-grid">
                                    <div>
                                        <label for="nombre_envio">Nombre completo</label>
                                        <input id="nombre_envio" name="nombre_envio" value="{{ old('nombre_envio', auth()->user()->name) }}" required>
                                    </div>

                                    <div>
                                        <label for="pais_envio">Pais</label>
                                        <input id="pais_envio" name="pais_envio" value="{{ old('pais_envio', 'Colombia') }}" required>
                                    </div>

                                    <div>
                                        <label for="ciudad_envio">Ciudad</label>
                                        <input id="ciudad_envio" name="ciudad_envio" value="{{ old('ciudad_envio') }}" required>
                                    </div>

                                    <div>
                                        <label for="direccion_envio">Direccion</label>
                                        <input id="direccion_envio" name="direccion_envio" value="{{ old('direccion_envio') }}" required>
                                    </div>
                                </div>

                                @if($errors->any())
                                    <div class="alert-error checkout-error">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <div class="payment-options">
                                    <input type="hidden" name="metodo_pago" value="nequi">
                                    <button type="submit" class="payment-option enabled">
                                        <strong>Nequi</strong>
                                        <span>Metodo disponible</span>
                                    </button>

                                    <button type="button" class="payment-option" disabled>
                                        <strong>Tarjeta</strong>
                                        <span>Proximamente</span>
                                    </button>

                                    <button type="button" class="payment-option" disabled>
                                        <strong>Daviplata</strong>
                                        <span>Proximamente</span>
                                    </button>

                                    <button type="button" class="payment-option" disabled>
                                        <strong>Transferencia</strong>
                                        <span>Proximamente</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="empty-cart">
                        <p>Tu carrito esta vacio.</p>
                    </div>
                @endif
            @endif
        </section>
    @endif

    <div class="shop-layout">
        <section class="card">
            <div class="card-header">
                <div>
                    <h2>Catalogo visual</h2>
                    <p class="page-description">Explora las zapatillas y agrega tus favoritas al carrito.</p>
                </div>
                @if(auth()->user()->role === 'admin')
                    <div class="actions">
                        <a href="{{ route('productos.create') }}" class="btn">Agregar producto</a>
                    </div>
                @endif
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
                                <p class="product-description">{{ $producto->descripcion ?: 'No hay descripcion disponible.' }}</p>
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
                                @if(auth()->user()->role === 'normal')
                                    <button type="button" class="btn product-cart-button" wire:click="agregarProducto({{ $producto->id }})">
                                        Agregar al carrito
                                    </button>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($productos->hasPages())
                    <nav class="catalog-pagination" aria-label="Paginacion del catalogo">
                        @if($productos->onFirstPage())
                            <span class="pagination-link disabled">Anterior</span>
                        @else
                            <button type="button" wire:click="previousPage" class="pagination-link">Anterior</button>
                        @endif

                        <div class="pagination-pages">
                            @foreach($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                                @if($page === $productos->currentPage())
                                    <span class="pagination-link active">{{ $page }}</span>
                                @else
                                    <button type="button" wire:click="gotoPage({{ $page }})" class="pagination-link">{{ $page }}</button>
                                @endif
                            @endforeach
                        </div>

                        @if($productos->hasMorePages())
                            <button type="button" wire:click="nextPage" class="pagination-link">Siguiente</button>
                        @else
                            <span class="pagination-link disabled">Siguiente</span>
                        @endif
                    </nav>
                @endif
            @else
                <div class="empty-state">
                    <p>No hay productos aun. Agrega zapatillas para verlas en el catalogo.</p>
                </div>
            @endif
        </section>

    </div>
</div>

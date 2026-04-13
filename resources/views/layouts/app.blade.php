<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Zapadictos')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="site-shell">
        <header class="site-header">
            <a href="{{ route('productos.index') }}" class="brand">
                <span class="brand-mark">Z</span>
                <div>
                    <strong>Zapadictos</strong>
                    <small>Calzado urbano</small>
                </div>
            </a>
            <nav class="site-nav">
                <a href="{{ route('productos.index') }}">Productos</a>
                <a href="{{ route('productos.catalogo') }}">Catálogo</a>
                <a href="{{ route('promociones.index') }}">Promociones</a>
                <a href="{{ route('productos.pdf') }}">Catálogo PDF</a>
            </nav>
        </header>

        <main class="page-content">
            <div class="hero-card">
                <div>
                    <h1>@yield('page-title', 'Inventario Zapadictos')</h1>
                    <p class="page-description">@yield('page-description', 'Gestiona tus productos y promociones con un ambiente inspirado en tiendas de calzado.')</p>
                </div>
                <div class="hero-badge">Shop style</div>
            </div>

            @yield('content')
        </main>

        <footer class="site-footer">
            <p>Zapadictos — Administra tus productos y promociones de forma moderna.</p>
        </footer>
    </div>
</body>
</html>

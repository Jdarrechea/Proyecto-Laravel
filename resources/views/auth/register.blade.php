<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro | Zapadictos</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <main class="login-screen">
        <form method="POST" action="{{ route('register.store') }}" class="card login-card">
            @csrf

            <div class="login-title">
                <span class="brand-mark">Z</span>
                <h1>Crear cuenta</h1>
                <p>Registrate como usuario para comprar en el catalogo.</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="form-grid">
                <div>
                    <label for="name">Nombre completo</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        required
                        autofocus
                    >
                </div>

                <div>
                    <label for="email">Correo electronico</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                    >
                </div>

                <div>
                    <label for="password">Contrasena</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div>
                    <label for="password_confirmation">Confirmar contrasena</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn login-button">Crear cuenta</button>
                <a href="{{ route('login.type', 'normal') }}" class="btn-secondary login-button">Ya tengo cuenta</a>
                <a href="{{ route('login') }}" class="btn-secondary login-button">Cambiar tipo de usuario</a>
            </div>
        </form>
    </main>
</body>
</html>

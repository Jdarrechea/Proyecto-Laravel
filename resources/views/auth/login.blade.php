<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | Zapadictos</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <main class="login-screen">
        <form method="POST" action="{{ route('login.store') }}" class="card login-card">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="login-title">
                <span class="brand-mark">Z</span>
                <h1>{{ $title }}</h1>
                <p>{{ $description }}</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="form-grid">
                <div>
                    <label for="email">Correo electronico</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        autofocus
                    >
                </div>

                <div>
                    <label for="password">Contrasena</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <label class="checkbox-label">
                    <input type="checkbox" name="remember" value="1">
                    Recordarme
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn login-button">Entrar</button>
                @if($type === 'normal')
                    <a href="{{ route('register') }}" class="btn-secondary login-button">Registrarse como usuario</a>
                @endif
                <a href="{{ route('login') }}" class="btn-secondary login-button">Cambiar tipo de usuario</a>
            </div>
        </form>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tipo de acceso | Zapadictos</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <main class="login-screen login-choice-screen">
        <section class="card login-card">
            <div class="login-title">
                <span class="brand-mark">Z</span>
                <h1>Como quieres iniciar?</h1>
                <p>Elige el tipo de usuario para continuar.</p>
            </div>

            <div class="login-choice-grid">
                <a href="{{ route('login.type', 'admin') }}" class="login-choice">
                    <strong>Usuario administrativo</strong>
                    <span>Gestionar productos y promociones</span>
                </a>

                <a href="{{ route('login.type', 'normal') }}" class="login-choice">
                    <strong>Usuario normal</strong>
                    <span>Consultar el catalogo</span>
                </a>
            </div>
        </section>
    </main>
</body>
</html>

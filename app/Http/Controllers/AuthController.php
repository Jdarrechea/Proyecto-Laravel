<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.choose-login');
    }

    public function showLoginForm(string $type)
    {
        if (! in_array($type, ['admin', 'normal'], true)) {
            abort(404);
        }

        return view('auth.login', [
            'type' => $type,
            'title' => $type === 'admin' ? 'Usuario administrativo' : 'Usuario normal',
            'description' => $type === 'admin'
                ? 'Accede para administrar productos y promociones.'
                : 'Accede para consultar el catalogo.',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'type' => ['required', 'in:admin,normal'],
        ]);

        $remember = $request->boolean('remember');
        $type = $credentials['type'];
        unset($credentials['type']);

        if (Auth::attempt($credentials, $remember)) {
            if ($request->user()->role !== $type) {
                Auth::logout();

                return back()
                    ->withErrors(['email' => 'Este usuario no corresponde al tipo de acceso seleccionado.'])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(
                $type === 'admin' ? route('productos.index') : route('productos.catalogo')
            );
        }

        return back()
            ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'normal',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('productos.catalogo');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

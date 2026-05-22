<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VentaController extends Controller
{
    public function index(): View
    {
        $ventas = Venta::with(['usuario', 'detalles.producto'])
            ->latest()
            ->paginate(10);

        $totalVentas = Venta::sum('total');
        $cantidadPedidos = Venta::count();
        $productosVendidos = DB::table('venta_detalles')->sum('cantidad');

        return view('ventas.index', compact(
            'ventas',
            'totalVentas',
            'cantidadPedidos',
            'productosVendidos'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()?->role === 'admin') {
            abort(403);
        }

        $request->validate([
            'metodo_pago' => ['required', 'in:nequi'],
            'nombre_envio' => ['required', 'string', 'max:255'],
            'pais_envio' => ['required', 'string', 'max:120'],
            'ciudad_envio' => ['required', 'string', 'max:120'],
            'direccion_envio' => ['required', 'string', 'max:255'],
        ]);

        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('productos.catalogo');
        }

        $cliente = $request->user() ?: $this->clienteWeb();

        $venta = DB::transaction(function () use ($carrito, $request, $cliente) {
            $detalles = [];
            $total = 0;

            foreach ($carrito as $item) {
                $producto = Producto::with('promocionActiva')->findOrFail($item['id']);
                $cantidad = min((int) $item['cantidad'], $producto->stock);

                if ($cantidad <= 0) {
                    continue;
                }

                $precio = $producto->precio_con_descuento;
                $subtotal = $precio * $cantidad;
                $total += $subtotal;

                $detalles[] = [
                    'producto' => $producto,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'subtotal' => $subtotal,
                ];
            }

            abort_if(empty($detalles), 422);

            $venta = Venta::create([
                'user_id' => $cliente->id,
                'total' => $total,
                'estado_pago' => 'pendiente',
                'metodo_pago' => 'nequi',
                'numero_pago' => '3170650834',
                'nombre_envio' => $request->nombre_envio,
                'pais_envio' => $request->pais_envio,
                'ciudad_envio' => $request->ciudad_envio,
                'direccion_envio' => $request->direccion_envio,
                'referencia_pago' => 'PAY-' . now()->format('YmdHis') . '-' . $cliente->id,
            ]);

            foreach ($detalles as $detalle) {
                $producto = $detalle['producto'];

                $venta->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio'],
                    'subtotal' => $detalle['subtotal'],
                ]);

                $producto->decrement('stock', $detalle['cantidad']);
            }

            return $venta;
        });

        session()->forget('carrito');
        session()->push('pedidos_realizados', $venta->id);

        return redirect()->route('ventas.show', $venta);
    }

    public function destroyHistory(): RedirectResponse
    {
        Venta::query()->delete();
        $this->reiniciarConsecutivoPedidos();

        return redirect()->route('ventas.index');
    }

    private function reiniciarConsecutivoPedidos(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement("DELETE FROM sqlite_sequence WHERE name IN ('ventas', 'venta_detalles')");
            return;
        }

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE venta_detalles AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE ventas AUTO_INCREMENT = 1');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement("ALTER SEQUENCE ventas_id_seq RESTART WITH 1");
            DB::statement("ALTER SEQUENCE venta_detalles_id_seq RESTART WITH 1");
        }
    }

    public function show(Venta $venta): View
    {
        $user = auth()->user();
        $pedidosRealizados = session('pedidos_realizados', []);
        $pedidoDeLaSesion = in_array($venta->id, $pedidosRealizados, true);
        $pedidoDelUsuario = $user && $venta->user_id === $user->id;
        $esAdmin = $user && $user->role === 'admin';

        abort_unless($pedidoDeLaSesion || $pedidoDelUsuario || $esAdmin, 403);

        $venta->load('detalles.producto', 'usuario');

        return view('ventas.show', compact('venta'));
    }

    private function clienteWeb(): User
    {
        return User::firstOrCreate(
            ['email' => 'cliente@zapadictos.local'],
            [
                'name' => 'Cliente Web',
                'password' => bcrypt(Str::random(32)),
                'role' => 'normal',
            ]
        );
    }
}

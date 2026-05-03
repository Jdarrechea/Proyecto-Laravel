<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $venta = DB::transaction(function () use ($carrito, $request) {
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
                'user_id' => auth()->id(),
                'total' => $total,
                'estado_pago' => 'pendiente',
                'metodo_pago' => 'nequi',
                'numero_pago' => '3170650834',
                'nombre_envio' => $request->nombre_envio,
                'pais_envio' => $request->pais_envio,
                'ciudad_envio' => $request->ciudad_envio,
                'direccion_envio' => $request->direccion_envio,
                'referencia_pago' => 'PAY-' . now()->format('YmdHis') . '-' . auth()->id(),
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
        abort_unless($venta->user_id === auth()->id() || auth()->user()->role === 'admin', 403);

        $venta->load('detalles.producto', 'usuario');

        return view('ventas.show', compact('venta'));
    }
}

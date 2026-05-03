<?php

namespace App\Http\Livewire;

use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogoCarrito extends Component
{
    use WithPagination;

    public array $carrito = [];
    public bool $carritoAbierto = false;
    public bool $mostrarMetodosPago = false;

    public function mount(): void
    {
        $this->carrito = session('carrito', []);
    }

    public function agregarProducto(int $productoId): void
    {
        $producto = Producto::with('promocionActiva')->findOrFail($productoId);
        $precio = $producto->precio_con_descuento;
        $imagen = $producto->imagen
            ? (str_starts_with($producto->imagen, 'http') ? $producto->imagen : asset('storage/' . $producto->imagen))
            : 'https://via.placeholder.com/520x360?text=Zapatillas+Zapadictos';

        if (isset($this->carrito[$productoId])) {
            $this->carrito[$productoId]['cantidad']++;
        } else {
            $this->carrito[$productoId] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'marca' => $producto->marca,
                'imagen' => $imagen,
                'precio' => $precio,
                'cantidad' => 1,
            ];
        }

        $this->guardarCarrito();
        $this->mostrarMetodosPago = false;
    }

    public function incrementar(int $productoId): void
    {
        if (isset($this->carrito[$productoId])) {
            $this->carrito[$productoId]['cantidad']++;
            $this->guardarCarrito();
            $this->mostrarMetodosPago = false;
        }
    }

    public function disminuir(int $productoId): void
    {
        if (! isset($this->carrito[$productoId])) {
            return;
        }

        $this->carrito[$productoId]['cantidad']--;

        if ($this->carrito[$productoId]['cantidad'] <= 0) {
            unset($this->carrito[$productoId]);
        }

        $this->guardarCarrito();
        $this->mostrarMetodosPago = false;
    }

    public function quitarProducto(int $productoId): void
    {
        unset($this->carrito[$productoId]);
        $this->guardarCarrito();
        $this->mostrarMetodosPago = false;
    }

    public function vaciarCarrito(): void
    {
        $this->carrito = [];
        $this->mostrarMetodosPago = false;
        session()->forget('carrito');
    }

    public function alternarCarrito(): void
    {
        $this->carritoAbierto = ! $this->carritoAbierto;
    }

    public function mostrarPago(): void
    {
        $this->carritoAbierto = true;
        $this->mostrarMetodosPago = true;
    }

    public function getTotalProperty(): float
    {
        return collect($this->carrito)->sum(fn ($item) => $item['precio'] * $item['cantidad']);
    }

    public function getCantidadProductosProperty(): int
    {
        return collect($this->carrito)->sum('cantidad');
    }

    protected function guardarCarrito(): void
    {
        session(['carrito' => $this->carrito]);
    }

    public function render()
    {
        return view('livewire.catalogo-carrito', [
            'productos' => Producto::with('promocionActiva')->latest()->paginate(9),
        ]);
    }
}

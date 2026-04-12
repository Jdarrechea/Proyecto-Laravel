<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $productos = Producto::where('nombre', 'like', "%$q%")
            ->orWhere('marca', 'like', "%$q%")
            ->paginate(10);

        return view('productos.index', compact('productos', 'q'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        Producto::create($request->all());
        return redirect()->route('productos.index');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $producto->update($request->all());
        return redirect()->route('productos.index');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index');
    }
    public function pdf()
{
    $productos = Producto::all();

    $pdf = Pdf::loadView('productos.pdf', compact('productos'));

    return $pdf->download('catalogo-zapadictos.pdf');
}
}
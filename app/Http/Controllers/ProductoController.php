<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function show(Producto $producto)
    {
        return redirect()->route('productos.index');
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'precio' => 'required|string',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:5120',
            'descripcion' => 'nullable|string',
        ]);

        $data['precio'] = (float) str_replace(',', '.', $data['precio']);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);
        return redirect()->route('productos.index');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'precio' => 'required|string',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:5120',
            'descripcion' => 'nullable|string',
        ]);

        $data['precio'] = (float) str_replace(',', '.', $data['precio']);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        } else {
            unset($data['imagen']);
        }

        $producto->update($data);
        return redirect()->route('productos.index');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index');
    }

    public function catalogo()
    {
        $productos = Producto::with('promocionActiva')->get();
        return view('productos.catalogo', compact('productos'));
    }

    public function pdf()
    {
        $productos = Producto::all();

        $pdf = Pdf::loadView('productos.pdf', compact('productos'));

        return $pdf->download('catalogo-zapadictos.pdf');
    }
}

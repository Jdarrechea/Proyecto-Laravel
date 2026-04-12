<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\Producto;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
    // LISTAR PROMOCIONES
    public function index()
    {
        $promociones = Promocion::with('producto')->paginate(10);
        return view('promociones.index', compact('promociones'));
    }

    // FORMULARIO CREAR
    public function create()
    {
        $productos = Producto::all();
        return view('promociones.create', compact('productos'));
    }

    // GUARDAR
    public function store(Request $request)
{
    Promocion::create($request->all());
    return redirect()->route('promociones.index');
}


    // ELIMINAR
    public function destroy(Promocion $promocion)
    {
        $promocion->delete();
        return redirect()->route('promociones.index');
    }
}
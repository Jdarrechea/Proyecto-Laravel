<h1>Nueva Promoción</h1>

{{ route('promociones.store') }}
@csrf

<label>Producto:</label><br>
<select name="producto_id">
    @foreach($productos as $prod)
        <option value="{{ $prod->id }}">{{ $prod->nombre }}</option>
    @endforeach
</select>
<br><br>

<label>Descuento (%):</label><br>
<input name="descuento"><br><br>

<label>Fecha inicio:</label><br>
<input type="date" name="fecha_inicio"><br><br>

<label>Fecha fin:</label><br>
<input type="date" name="fecha_fin"><br><br>

<button>Guardar</button>
</form>

{{ route('promociones.index') }}Volver</a>
``
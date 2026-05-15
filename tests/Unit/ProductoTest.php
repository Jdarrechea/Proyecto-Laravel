<?php

namespace Tests\Unit;

use App\Models\Producto;
use App\Models\Promocion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_producto_puede_ser_creado()
    {
        $producto = Producto::create([
            'nombre' => 'Laptop HP',
            'marca' => 'HP',
            'categoria' => 'Electrónica',
            'precio' => 1500.00,
            'stock' => 10,
            'descripcion' => 'Laptop de alto rendimiento'
        ]);

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Laptop HP'
        ]);
    }

    /** @test */
    public function test_precio_sin_promocion_es_igual_al_precio_base()
    {
        $producto = Producto::create([
            'nombre' => 'Mouse',
            'marca' => 'Logitech',
            'categoria' => 'Periféricos',
            'precio' => 50.00,
            'stock' => 20,
        ]);

        $this->assertEquals(50.00, $producto->precio_con_descuento);
    }

    /** @test */
    public function test_precio_con_promocion_activa_calcula_descuento_correctamente()
    {
        $producto = Producto::create([
            'nombre' => 'Teclado',
            'marca' => 'Corsair',
            'categoria' => 'Periféricos',
            'precio' => 100.00,
            'stock' => 15,
        ]);

        Promocion::create([
            'producto_id' => $producto->id,
            'descuento' => 20, // 20% descuento
            'fecha_inicio' => now()->subDay(),
            'fecha_fin' => now()->addDay(),
            'activa' => true,
        ]);

        // Precio esperado: 100 * (1 - 0.20) = 80.00
        $this->assertEquals(80.00, $producto->refresh()->precio_con_descuento);
    }

    /** @test */
    public function test_promocion_expirada_no_aplica_descuento()
    {
        $producto = Producto::create([
            'nombre' => 'Monitor',
            'marca' => 'Samsung',
            'categoria' => 'Electrónica',
            'precio' => 300.00,
            'stock' => 5,
        ]);

        Promocion::create([
            'producto_id' => $producto->id,
            'descuento' => 30,
            'fecha_inicio' => now()->subDays(5),
            'fecha_fin' => now()->subDay(), // Expirada
            'activa' => true,
        ]);

        $this->assertEquals(300.00, $producto->refresh()->precio_con_descuento);
    }

    /** @test */
    public function test_relacion_promociones()
    {
        $producto = Producto::create([
            'nombre' => 'Headphones',
            'marca' => 'Sony',
            'categoria' => 'Audio',
            'precio' => 200.00,
            'stock' => 8,
        ]);

        Promocion::create([
            'producto_id' => $producto->id,
            'descuento' => 15,
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addDays(7),
            'activa' => true,
        ]);

        $this->assertCount(1, $producto->promociones);
        $this->assertEquals('Sony', $producto->promociones->first()->producto->marca);
    }

    /** @test */
    public function test_atributos_requeridos()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Producto::create([
            'nombre' => 'Producto Incompleto',
            // Faltan otros atributos requeridos
        ]);
    }

    /** @test */
    public function test_casting_precio_a_float()
    {
        $producto = Producto::create([
            'nombre' => 'Producto',
            'marca' => 'Marca',
            'categoria' => 'Categoría',
            'precio' => '99.99',
            'stock' => 10,
        ]);

        $this->assertIsFloat($producto->precio);
        $this->assertEquals(99.99, $producto->precio);
    }

    /** @test */
    public function test_casting_stock_a_integer()
    {
        $producto = Producto::create([
            'nombre' => 'Producto',
            'marca' => 'Marca',
            'categoria' => 'Categoría',
            'precio' => 50.00,
            'stock' => '15',
        ]);

        $this->assertIsInt($producto->stock);
        $this->assertEquals(15, $producto->stock);
    }
}

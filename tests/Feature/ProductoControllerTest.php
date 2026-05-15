<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\Promocion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_admin_puede_listar_productos()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Producto::factory(3)->create();

        $response = $this->actingAs($admin)->get('/productos');

        $response->assertStatus(200);
        $response->assertViewHas('productos');
    }

    /** @test */
    public function test_usuario_no_autenticado_no_puede_listar_productos()
    {
        Producto::factory(3)->create();

        $response = $this->get('/productos');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_admin_show_producto_redirige_a_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $producto = Producto::create([
            'nombre' => 'Laptop',
            'marca' => 'Dell',
            'categoria' => 'Computadoras',
            'precio' => 1000.00,
            'stock' => 5,
            'descripcion' => 'Laptop de alto desempeño'
        ]);

        $response = $this->actingAs($admin)->get("/productos/{$producto->id}");

        $response->assertRedirect(route('productos.index'));
    }

    /** @test */
    public function test_producto_no_encontrado_retorna_404()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/productos/999');

        $response->assertNotFound();
    }

    /** @test */
    public function test_admin_puede_crear_producto()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/productos', [
            'nombre' => 'Mouse Inalámbrico',
            'marca' => 'Logitech',
            'categoria' => 'Periféricos',
            'precio' => '45.99',
            'stock' => 20,
            'descripcion' => 'Mouse inalámbrico con batería larga'
        ]);

        $this->assertDatabaseHas('productos', [
            'nombre' => 'Mouse Inalámbrico',
            'marca' => 'Logitech'
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function test_usuario_normal_no_puede_crear_producto()
    {
        $customer = User::factory()->create(['role' => 'normal']);

        $response = $this->actingAs($customer)->post('/productos', [
            'nombre' => 'Producto',
            'marca' => 'Marca',
            'categoria' => 'Cat',
            'precio' => 100.00,
            'stock' => 10,
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function test_admin_puede_actualizar_producto()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $producto = Producto::factory()->create(['nombre' => 'Producto Original']);

        $response = $this->actingAs($admin)->put("/productos/{$producto->id}", [
            'nombre' => 'Producto Actualizado',
            'marca' => $producto->marca,
            'categoria' => $producto->categoria,
            'precio' => (string) $producto->precio,
            'stock' => $producto->stock,
            'descripcion' => $producto->descripcion,
        ]);

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Producto Actualizado'
        ]);
    }

    /** @test */
    public function test_admin_puede_eliminar_producto()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $producto = Producto::factory()->create();
        $productoId = $producto->id;

        $response = $this->actingAs($admin)->delete("/productos/{$producto->id}");

        $this->assertDatabaseMissing('productos', ['id' => $productoId]);
    }

    /** @test */
    public function test_producto_con_promocion_calcula_precio_con_descuento_correctamente()
    {
        $producto = Producto::create([
            'nombre' => 'Teclado Gaming',
            'marca' => 'Corsair',
            'categoria' => 'Periféricos',
            'precio' => 150.00,
            'stock' => 10,
        ]);

        Promocion::create([
            'producto_id' => $producto->id,
            'descuento' => 25,
            'fecha_inicio' => now()->subDay(),
            'fecha_fin' => now()->addDay(),
            'activa' => true,
        ]);

        $this->assertEquals(112.50, $producto->refresh()->precio_con_descuento);
    }

    /** @test */
    public function test_validacion_precio_requerido()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/productos', [
            'nombre' => 'Producto',
            'marca' => 'Marca',
            'categoria' => 'Cat',
            'precio' => '', // vacío
            'stock' => 10,
        ]);

        $response->assertSessionHasErrors('precio');
    }

    /** @test */
    public function test_validacion_stock_no_negativo()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/productos', [
            'nombre' => 'Producto',
            'marca' => 'Marca',
            'categoria' => 'Cat',
            'precio' => 100.00,
            'stock' => -5, // negativo
        ]);

        $response->assertSessionHasErrors('stock');
    }
}

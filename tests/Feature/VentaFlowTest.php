<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VentaFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_usuario_normal_puede_crear_venta_desde_carrito()
    {
        $user = User::factory()->create(['role' => 'normal']);
        $producto = Producto::create([
            'nombre' => 'Laptop',
            'marca' => 'Dell',
            'categoria' => 'Computadoras',
            'precio' => 1000.00,
            'stock' => 5,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['carrito' => [
                ['id' => $producto->id, 'cantidad' => 2],
            ]])
            ->post('/ventas', [
                'metodo_pago' => 'nequi',
                'nombre_envio' => 'Juan Pérez',
                'pais_envio' => 'Colombia',
                'ciudad_envio' => 'Bogotá',
                'direccion_envio' => 'Calle 1 #123',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ventas', [
            'user_id' => $user->id,
            'estado_pago' => 'pendiente',
            'nombre_envio' => 'Juan Pérez',
        ]);
        $this->assertEquals(3, $producto->refresh()->stock);
    }

    /** @test */
    public function test_venta_reduce_stock_del_producto()
    {
        $user = User::factory()->create(['role' => 'normal']);
        $producto = Producto::create([
            'nombre' => 'Mouse',
            'marca' => 'Logitech',
            'categoria' => 'Periféricos',
            'precio' => 50.00,
            'stock' => 10,
        ]);

        $this->actingAs($user)
            ->withSession(['carrito' => [
                ['id' => $producto->id, 'cantidad' => 2],
            ]])
            ->post('/ventas', [
                'metodo_pago' => 'nequi',
                'nombre_envio' => 'Juan Pérez',
                'pais_envio' => 'Colombia',
                'ciudad_envio' => 'Bogotá',
                'direccion_envio' => 'Calle 1 #123',
            ]);

        $this->assertEquals(8, $producto->refresh()->stock);
    }

    /** @test */
    public function test_usuario_puede_ver_venta_propia()
    {
        $user = User::factory()->create(['role' => 'normal']);

        $venta = Venta::create([
            'user_id' => $user->id,
            'total' => 150.00,
            'estado_pago' => 'completado',
            'metodo_pago' => 'nequi',
            'nombre_envio' => 'Juan Pérez',
            'pais_envio' => 'Colombia',
            'ciudad_envio' => 'Bogotá',
            'direccion_envio' => 'Calle 1 #123',
        ]);

        $response = $this->actingAs($user)->get(route('ventas.show', $venta));

        $response->assertStatus(200);
        $response->assertViewHas('venta');
    }

    /** @test */
    public function test_venta_calcula_total_correctamente()
    {
        $user = User::factory()->create();
        $producto1 = Producto::factory()->create(['precio' => 100.00]);
        $producto2 = Producto::factory()->create(['precio' => 50.00]);

        $venta = Venta::create([
            'user_id' => $user->id,
            'total' => 250.00,
            'estado_pago' => 'completado',
            'metodo_pago' => 'nequi',
        ]);

        VentaDetalle::create([
            'venta_id' => $venta->id,
            'producto_id' => $producto1->id,
            'cantidad' => 1,
            'precio_unitario' => 100.00,
            'subtotal' => 100.00,
        ]);

        VentaDetalle::create([
            'venta_id' => $venta->id,
            'producto_id' => $producto2->id,
            'cantidad' => 3,
            'precio_unitario' => 50.00,
            'subtotal' => 150.00,
        ]);

        $this->assertEquals(250.00, $venta->total);
    }

    /** @test */
    public function test_no_puede_comprar_producto_sin_stock()
    {
        $user = User::factory()->create(['role' => 'normal']);
        $producto = Producto::create([
            'nombre' => 'Producto',
            'marca' => 'Marca',
            'categoria' => 'Cat',
            'precio' => 100.00,
            'stock' => 0,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['carrito' => [
                ['id' => $producto->id, 'cantidad' => 1],
            ]])
            ->post('/ventas', [
                'metodo_pago' => 'nequi',
                'nombre_envio' => 'Pedro Gómez',
                'pais_envio' => 'España',
                'ciudad_envio' => 'Madrid',
                'direccion_envio' => 'Calle Principal 456',
            ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function test_venta_registra_direccion_envio()
    {
        $user = User::factory()->create(['role' => 'normal']);
        $producto = Producto::create([
            'nombre' => 'Producto',
            'marca' => 'Marca',
            'categoria' => 'Cat',
            'precio' => 100.00,
            'stock' => 5,
        ]);

        $this->actingAs($user)
            ->withSession(['carrito' => [
                ['id' => $producto->id, 'cantidad' => 1],
            ]])
            ->post('/ventas', [
                'metodo_pago' => 'nequi',
                'nombre_envio' => 'Pedro Gómez',
                'pais_envio' => 'España',
                'ciudad_envio' => 'Madrid',
                'direccion_envio' => 'Calle Principal 456',
            ]);

        $this->assertDatabaseHas('ventas', [
            'user_id' => $user->id,
            'nombre_envio' => 'Pedro Gómez',
            'pais_envio' => 'España',
            'ciudad_envio' => 'Madrid',
        ]);
    }

    /** @test */
    public function test_pago_rechazado_marca_venta_como_rechazada()
    {
        $user = User::factory()->create();

        $venta = Venta::create([
            'user_id' => $user->id,
            'total' => 100.00,
            'estado_pago' => 'rechazado',
            'metodo_pago' => 'nequi',
        ]);

        $this->assertEquals('rechazado', $venta->estado_pago);
    }
}

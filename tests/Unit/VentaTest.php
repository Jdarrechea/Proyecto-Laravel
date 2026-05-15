<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VentaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_venta_puede_ser_creada()
    {
        $user = User::factory()->create();

        $venta = Venta::create([
            'user_id' => $user->id,
            'total' => 250.00,
            'estado_pago' => 'pendiente',
            'metodo_pago' => 'tarjeta_credito',
            'numero_pago' => 'PAY-12345',
            'nombre_envio' => 'Juan Pérez',
            'pais_envio' => 'Colombia',
            'ciudad_envio' => 'Bogotá',
            'direccion_envio' => 'Calle 1 #123',
        ]);

        $this->assertDatabaseHas('ventas', [
            'id' => $venta->id,
            'total' => 250.00,
            'estado_pago' => 'pendiente'
        ]);
    }

    /** @test */
    public function test_venta_pertenece_a_un_usuario()
    {
        $user = User::factory()->create();
        $venta = Venta::create([
            'user_id' => $user->id,
            'total' => 100.00,
            'estado_pago' => 'completado',
            'metodo_pago' => 'paypal',
        ]);

        $this->assertTrue($venta->usuario->is($user));
    }

    /** @test */
    public function test_venta_tiene_muchos_detalles()
    {
        $user = User::factory()->create();
        $venta = Venta::create([
            'user_id' => $user->id,
            'total' => 500.00,
            'estado_pago' => 'completado',
            'metodo_pago' => 'tarjeta_credito',
        ]);

        $producto1 = Producto::factory()->create();
        $producto2 = Producto::factory()->create();

        VentaDetalle::create([
            'venta_id' => $venta->id,
            'producto_id' => $producto1->id,
            'cantidad' => 2,
            'precio_unitario' => 100.00,
            'subtotal' => 200.00,
        ]);

        VentaDetalle::create([
            'venta_id' => $venta->id,
            'producto_id' => $producto2->id,
            'cantidad' => 3,
            'precio_unitario' => 100.00,
            'subtotal' => 300.00,
        ]);

        $this->assertCount(2, $venta->detalles);
    }

    /** @test */
    public function test_venta_total_es_float()
    {
        $user = User::factory()->create();
        $venta = Venta::create([
            'user_id' => $user->id,
            'total' => '1250.75',
            'estado_pago' => 'completado',
            'metodo_pago' => 'transferencia',
        ]);

        $this->assertIsFloat($venta->total);
        $this->assertEquals(1250.75, $venta->total);
    }

    /** @test */
    public function test_venta_estados_pago_validos()
    {
        $user = User::factory()->create();
        $estados = ['pendiente', 'completado', 'rechazado', 'reembolso'];

        foreach ($estados as $estado) {
            $venta = Venta::create([
                'user_id' => $user->id,
                'total' => 100.00,
                'estado_pago' => $estado,
                'metodo_pago' => 'tarjeta_credito',
            ]);

            $this->assertEquals($estado, $venta->estado_pago);
        }
    }

    /** @test */
    public function test_eliminar_venta_elimina_detalles()
    {
        $user = User::factory()->create();
        $venta = Venta::create([
            'user_id' => $user->id,
            'total' => 500.00,
            'estado_pago' => 'completado',
            'metodo_pago' => 'tarjeta_credito',
        ]);

        $producto = Producto::factory()->create();

        VentaDetalle::create([
            'venta_id' => $venta->id,
            'producto_id' => $producto->id,
            'cantidad' => 1,
            'precio_unitario' => 100.00,
            'subtotal' => 100.00,
        ]);

        $ventaId = $venta->id;
        $venta->delete();

        $this->assertDatabaseMissing('ventas', ['id' => $ventaId]);
    }
}

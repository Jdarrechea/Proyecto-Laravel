<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_usuario_puede_ser_creado()
    {
        $user = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'juan@example.com'
        ]);
    }

    /** @test */
    public function test_usuario_tiene_atributos_requeridos()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->password);
    }

    /** @test */
    public function test_contrasena_esta_hasheada()
    {
        $password = 'password123';
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $password,
        ]);

        $this->assertNotEquals($password, $user->password);
    }

    /** @test */
    public function test_contrasena_no_es_visible_en_serializacion()
    {
        $user = User::factory()->create();

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
    }

    /** @test */
    public function test_usuario_tiene_roles()
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $customerUser = User::factory()->create(['role' => 'customer']);

        $this->assertEquals('admin', $adminUser->role);
        $this->assertEquals('customer', $customerUser->role);
    }

    /** @test */
    public function test_usuario_puede_tener_multiples_tokens_api()
    {
        $user = User::factory()->create();

        $token1 = $user->createToken('app')->plainTextToken;
        $token2 = $user->createToken('mobile')->plainTextToken;

        $this->assertNotEquals($token1, $token2);
        $this->assertCount(2, $user->tokens);
    }

    /** @test */
    public function test_email_es_unico()
    {
        User::factory()->create(['email' => 'unique@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Another User',
            'email' => 'unique@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}

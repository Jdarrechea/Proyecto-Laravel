<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_usuario_no_autenticado_es_redirigido_a_login()
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_login_page_esta_disponible()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.choose-login');
    }

    /** @test */
    public function test_usuario_puede_ver_formulario_de_login_normal()
    {
        $response = $this->get('/login/normal');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /** @test */
    public function test_usuario_puede_autenticarse_con_credenciales_validas()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'normal',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'type' => 'normal',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('productos.catalogo'));
    }

    /** @test */
    public function test_usuario_no_puede_autenticarse_con_email_incorrecto()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'normal',
        ]);

        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
            'type' => 'normal',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_usuario_no_puede_autenticarse_con_contrasena_incorrecta()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'normal',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
            'type' => 'normal',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_usuario_autenticado_puede_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_usuario_autenticado_no_puede_acceder_a_login()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/');
    }

    /** @test */
    public function test_formulario_login_valida_email_requerido()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
            'type' => 'normal',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_formulario_login_valida_contrasena_requerida()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
            'type' => 'normal',
        ]);

        $response->assertSessionHasErrors('password');
    }
}

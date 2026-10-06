<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Lancar Barokah');
        $response->assertSee('Masuk ke Akun');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Operator Sablon',
            'username' => 'operator',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        Livewire::test(Login::class)
            ->set('username', 'operator')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_login(): void
    {
        User::create([
            'name' => 'User Nonaktif',
            'username' => 'nonaktif',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'is_active' => false,
        ]);

        Livewire::test(Login::class)
            ->set('username', 'nonaktif')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors(['username']);

        $this->assertGuest();
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::create([
            'name' => 'User Valid',
            'username' => 'uservalid',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        Livewire::test(Login::class)
            ->set('username', 'uservalid')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertHasErrors(['username']);

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'User Test',
            'username' => 'usertest',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
 
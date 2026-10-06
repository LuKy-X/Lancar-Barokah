<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_redirects_unauthenticated_user_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Demo Staff',
            'username' => 'demostaff',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get('/');
        $response->assertStatus(200);
    }
}

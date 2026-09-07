<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Precision Medical Invoicing');
    }

    public function test_login_page_renders_sso_interface(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Staff Portal Sign In');
        $response->assertSee('Sign in with CentraFlow SSO');
        $response->assertSee(route('sso.login'));
    }

    public function test_old_direct_post_login_route_is_not_available(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@clinic.my',
            'password' => 'password',
        ]);

        $response->assertStatus(405); // Method Not Allowed (POST /login does not exist)
    }

    public function test_old_forgot_password_route_is_not_available(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $this->assertGuest();

        $centraflowHost = rtrim(config('services.centraflow.host', env('CENTRAFLOW_HOST', 'http://localhost:8004')), '/');
        $returnUrl = url('/login?logged_out=1');
        $expectedRedirect = $centraflowHost . '/logout?redirect_uri=' . urlencode($returnUrl);

        $response->assertRedirect($expectedRedirect);
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/login');
        $response->assertRedirect('/dashboard');
    }
}

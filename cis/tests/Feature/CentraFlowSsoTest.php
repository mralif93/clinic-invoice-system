<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class CentraFlowSsoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sso_redirect_generates_state_and_redirects_to_centraflow(): void
    {
        $response = $this->get(route('sso.login'));

        $response->assertRedirect();
        $this->assertStringContainsString('http://localhost:8004/oauth/authorize', $response->headers->get('Location'));
        $this->assertStringContainsString('client_id=9d12a101-0003-4000-8000-000000000003', $response->headers->get('Location'));
        $this->assertStringContainsString('redirect_uri=' . urlencode('http://localhost:8003/auth/callback'), $response->headers->get('Location'));
        $this->assertTrue(session()->has('oauth_state'));
    }

    public function test_sso_callback_rejects_invalid_state(): void
    {
        session(['oauth_state' => 'valid-stored-state']);

        $response = $this->get(route('sso.callback', ['state' => 'mismatched-state', 'code' => 'test-code']));

        $response->assertStatus(403);
    }

    public function test_sso_callback_handles_authorization_denied(): void
    {
        $state = Str::random(40);
        session(['oauth_state' => $state]);

        $response = $this->get(route('sso.callback', [
            'state' => $state,
            'error' => 'access_denied',
        ]));

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['oauth']);
    }

    public function test_sso_callback_authenticates_user_successfully(): void
    {
        $state = Str::random(40);
        session(['oauth_state' => $state]);

        Http::fake([
            'http://localhost:8004/oauth/token' => Http::response([
                'access_token' => 'mocked-jwt-token',
                'token_type'   => 'Bearer',
                'expires_in'   => 3600,
            ], 200),
            'http://localhost:8004/api/v1/me' => Http::response([
                'status' => 'success',
                'data'   => [
                    'uuid'       => '11111111-2222-3333-4444-555555555555',
                    'name'       => 'Dr. Centra User',
                    'email'      => 'doctor.centra@clinic.my',
                    'department' => 'Clinical',
                    'role'       => 'doctor',
                ],
            ], 200),
        ]);

        $response = $this->get(route('sso.callback', [
            'state' => $state,
            'code'  => 'mocked-auth-code',
        ]));

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'doctor.centra@clinic.my')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Dr. Centra User', $user->name);
        $this->assertEquals('11111111-2222-3333-4444-555555555555', $user->centraflow_uuid);
        $this->assertEquals('mocked-jwt-token', session('centraflow_access_token'));
    }
}

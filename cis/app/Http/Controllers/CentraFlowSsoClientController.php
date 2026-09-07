<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CentraFlowSsoClientController extends Controller
{
    /**
     * Redirect the user to CentraFlow SSO authorization dialog.
     */
    public function redirect(Request $request)
    {
        // 1. Generate CSRF state token
        $state = Str::random(40);
        $request->session()->put('oauth_state', $state);

        // 2. Build OAuth authorization query
        $query = http_build_query([
            'client_id'     => config('services.centraflow.client_id', env('CENTRAFLOW_CLIENT_ID')),
            'redirect_uri'  => config('services.centraflow.redirect_uri', env('CENTRAFLOW_REDIRECT_URI')),
            'response_type' => 'code',
            'scope'         => config('services.centraflow.scopes', env('CENTRAFLOW_SCOPES', '')),
            'state'         => $state,
        ]);

        $authUrl = rtrim(config('services.centraflow.host', env('CENTRAFLOW_HOST')), '/') . '/oauth/authorize?' . $query;

        return redirect()->away($authUrl);
    }

    /**
     * Handle the OAuth callback from CentraFlow.
     */
    public function callback(Request $request)
    {
        // 1. Verify CSRF state token
        $savedState = $request->session()->pull('oauth_state');
        if (empty($savedState) || $savedState !== $request->query('state')) {
            abort(403, 'Invalid or expired OAuth state token.');
        }

        if ($request->has('error')) {
            return redirect('/login')->withErrors(['oauth' => 'CentraFlow authorization was denied.']);
        }

        // 2. Exchange authorization code for access token
        $tokenResponse = Http::asForm()->post(rtrim(config('services.centraflow.host', env('CENTRAFLOW_HOST')), '/') . '/oauth/token', [
            'grant_type'    => 'authorization_code',
            'client_id'     => config('services.centraflow.client_id', env('CENTRAFLOW_CLIENT_ID')),
            'client_secret' => config('services.centraflow.client_secret', env('CENTRAFLOW_CLIENT_SECRET')),
            'redirect_uri'  => config('services.centraflow.redirect_uri', env('CENTRAFLOW_REDIRECT_URI')),
            'code'          => $request->query('code'),
        ]);

        if (! $tokenResponse->successful()) {
            return redirect('/login')->withErrors(['oauth' => 'Could not exchange code with CentraFlow: ' . $tokenResponse->body()]);
        }

        $tokenPayload = $tokenResponse->json();
        $accessToken = $tokenPayload['access_token'];

        // 3. Retrieve user profile from CentraFlow master directory
        $userResponse = Http::withToken($accessToken)
            ->acceptJson()
            ->get(rtrim(config('services.centraflow.host', env('CENTRAFLOW_HOST')), '/') . '/api/v1/me');

        if (! $userResponse->successful()) {
            return redirect('/login')->withErrors(['oauth' => 'Failed retrieving profile from CentraFlow.']);
        }

        $profile = $userResponse->json('data');

        // 4. Find or provision user in local sub-system database
        $user = User::updateOrCreate(
            ['email' => $profile['email']],
            [
                'name'     => $profile['name'],
                // Set unguessable password since auth is handled by CentraFlow
                'password' => bcrypt(Str::random(32)),
            ]
        );

        // Optional: Save CentraFlow UUID or role if column exists
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'centraflow_uuid')) {
            $user->centraflow_uuid = $profile['uuid'] ?? null;
            $user->save();
        }

        // 5. Authenticate user into local session
        Auth::login($user, true);

        // Store token in session if sub-system needs to call CentraFlow APIs
        $request->session()->put('centraflow_access_token', $accessToken);

        return redirect()->intended('/dashboard');
    }

    /**
     * Show the SSO login landing page.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle user logout and clear session.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $centraflowHost = rtrim(config('services.centraflow.host', env('CENTRAFLOW_HOST', 'http://localhost:8004')), '/');
        $returnUrl = url('/login?logged_out=1');

        if (!empty($centraflowHost)) {
            return redirect()->away($centraflowHost . '/logout?redirect_uri=' . urlencode($returnUrl));
        }

        return redirect()->route('login')->with('status', 'You have been successfully signed out of ClinicFlow.');
    }
}

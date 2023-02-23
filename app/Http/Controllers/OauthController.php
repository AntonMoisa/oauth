<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Oauth\OauthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OauthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id'     => config('services.oauth.client_id'),
            'redirect_uri'  => config('services.oauth.redirect_uri'),
            'scope'         => 'account balances addresses transfer',
            'response_type' => 'code',
            'state'         => $state,
        ]);

        return redirect(config('services.oauth.api_url') . '/authorize?' . $query);
    }

    public function callback(Request $request): RedirectResponse
    {
        $state = $request->session()->pull('state');

        if (strlen($state) === 0 || $state !== $request->get('state')) {
            return redirect()->route('index')->with('message', 'Incorrect state');
        }

        if (! $code = $request->get('code')) {
            return redirect()->route('index')->with('message', 'Incorrect code');
        }

        $response = Http::asForm()->post(config('services.oauth.api_url') . '/token', [
            'grant_type'    => 'authorization_code',
            'client_id'     => config('services.oauth.client_id'),
            'client_secret' => config('services.oauth.client_secret'),
            'redirect_uri'  => config('services.oauth.redirect_uri'),
            'code'          => $code,
        ]);

        if ($response->failed()) {
            logger()->info('Oauth token request was failed', (array) $response->json());

            /** @var string $message */
            $message = $response->json('message', 'Something went wrong, try again later');

            return redirect()->route('index')->with('message', $message);
        }

        $accessToken = $response['access_token'];

        $client = new OauthService($accessToken);

        $user = $client->getUser();

        if (! $user) {
            return redirect()->route('index')->with('message', 'Failed to get user account');
        }

        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_email', $user->email);
        $request->session()->put('user_token', $accessToken);

        return redirect()->route('user');
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Oauth;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OauthService
{
    private PendingRequest $client;

    public function __construct(string $token)
    {
        $this->client = Http::withToken($token)->baseUrl(config('services.oauth.api_url'));
    }

    public function getUser(): ?UserObject
    {
        $response = $this->client->get('/account');

        if ($response->failed()) {
            return null;
        }

        return new UserObject(
            $response->json('data.id'),
            $response->json('data.email'),
        );
    }
}

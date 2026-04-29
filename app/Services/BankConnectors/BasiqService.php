<?php

namespace App\Services\BankConnectors;

use Illuminate\Support\Facades\Http;

class BasiqService
{
    protected function cfg(string $key, $default = null)
    {
        return setting('bank_connectors.basiq.' . $key, config('services.basiq.' . $key, $default));
    }

    public function authorizationUrl(string $state): string
    {
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => (string) $this->cfg('client_id'),
            'redirect_uri' => (string) $this->cfg('redirect_uri'),
            'scope' => (string) $this->cfg('scope', 'openid'),
            'state' => $state,
        ]);

        return rtrim((string) $this->cfg('auth_url'), '/') . '?' . $query;
    }

    public function exchangeCodeForToken(string $code): array
    {
        $response = Http::asForm()->acceptJson()->post((string) $this->cfg('token_url'), [
            'grant_type' => 'authorization_code',
            'client_id' => (string) $this->cfg('client_id'),
            'client_secret' => (string) $this->cfg('client_secret'),
            'redirect_uri' => (string) $this->cfg('redirect_uri'),
            'code' => $code,
        ]);

        if (! $response->successful()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'raw' => $response->body(),
            ];
        }

        $json = $response->json();

        return [
            'success' => true,
            'access_token' => (string) ($json['access_token'] ?? ''),
            'refresh_token' => (string) ($json['refresh_token'] ?? ''),
            'expires_in' => (int) ($json['expires_in'] ?? 0),
            'raw' => $json,
        ];
    }

    public function fetchStatements(string $accessToken): array
    {
        $baseUrl = rtrim((string) $this->cfg('base_url'), '/');
        $path = '/' . ltrim((string) $this->cfg('statements_path', '/users/me/transactions'), '/');

        $response = Http::withToken($accessToken)->acceptJson()->get($baseUrl . $path);

        if (! $response->successful()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'raw' => $response->body(),
                'data' => [],
            ];
        }

        return [
            'success' => true,
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }
}

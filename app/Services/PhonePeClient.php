<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class PhonePeClient
{
    private string $env;
    private array $urls;
    private string $clientId;
    private string $clientSecret;
    private string $clientVersion;
    private int $tokenSafety;

    public function __construct()
    {
        $this->env           = Config::get('phonepe.env', 'sandbox');
        $this->urls          = Config::get("phonepe.base_urls.{$this->env}");
        $this->clientId      = Config::get('phonepe.client_id');
        $this->clientSecret  = Config::get('phonepe.client_secret');
        $this->clientVersion = (string) Config::get('phonepe.client_version', '1');
        $this->tokenSafety   = (int) Config::get('phonepe.token_safety', 60);
    }

    public function getAccessToken(): string
    {
        return Cache::remember('phonepe_access_token', 3600, function () {
            $resp = Http::asForm()
                ->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded'])
                ->post("https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token", [
                    'client_id'     => $this->clientId,
                    'client_version'=> $this->clientVersion,
                    'client_secret' => $this->clientSecret,
                    'grant_type'    => 'client_credentials',
                ])->throw();

            $data = $resp->json();
            $token = $data['access_token'] ?? '';
            $expiresAt = $data['expires_at'] ?? null; // epoch seconds

            if (!$token) {
                throw new RequestException($resp);
            }

            if ($expiresAt) {
                // Cache only until (expiresAt - safety)
                $ttl = max(60, $expiresAt - time() - $this->tokenSafety);
                Cache::put('phonepe_access_token', $token, $ttl);
            }

            return $token;
        });
    }

    private function authHeaders(): array
    {
        return [
            'Authorization' => 'O-Bearer ' . $this->getAccessToken(),
            'Content-Type'  => 'application/json',
        ];
    }

    // === Create Order Token ===
    public function createOrderToken(array $payload): array
    {
        // Endpoint per docs: POST /checkout/v2/sdk/order
        $url = "{$this->urls['checkout']}/sdk/order";

        $response = Http::withHeaders($this->authHeaders())
            ->post($url, $payload)
            ->throw();

        return $response->json();
    }

    // === Check Order Status ===
    public function getOrderStatus(string $merchantOrderId, bool $details = false, bool $errorContext = false): array
    {
        // Endpoint per docs: GET /checkout/v2/order/{merchantOrderId}/status?details=&errorContext=
        $qs = http_build_query(['details' => $details ? 'true' : 'false', 'errorContext' => $errorContext ? 'true' : 'false']);
        $url = "{$this->urls['checkout']}/order/{$merchantOrderId}/status?{$qs}";

        $response = Http::withHeaders($this->authHeaders())
            ->get($url)
            ->throw();

        return $response->json();
    }

    // === Initiate Refund ===
    public function initiateRefund(array $payload): array
    {
        // Endpoint per docs: POST /payments/v2/refund
        $url = "{$this->urls['payments']}/refund";

        $response = Http::withHeaders($this->authHeaders())
            ->post($url, $payload)
            ->throw();

        return $response->json();
    }

    // === Refund Status ===
    public function getRefundStatus(string $merchantRefundId): array
    {
        // Endpoint per docs: GET /payments/v2/refund/{merchantRefundId}/status
        $url = "{$this->urls['payments']}/refund/{$merchantRefundId}/status";

        $response = Http::withHeaders($this->authHeaders())
            ->get($url)
            ->throw();

        return $response->json();
    }
}

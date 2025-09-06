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
        return Cache::remember('phonepe_access_token', now()->addMinutes(50), function () {
            $urls = config('phonepe.base_urls');
    
            $resp = Http::asForm()->post($urls['sandbox']['oauth'], [
                'client_id'      => config('phonepe.client_id'),
                'client_version' => config('phonepe.client_version'),
                'client_secret'  => config('phonepe.client_secret'),
                'grant_type'     => 'client_credentials',
            ]);
    
            // Helpful error if 4xx/5xx
            if (! $resp->successful()) {
                throw new \RuntimeException(
                    'PhonePe OAuth failed: '.$resp->status().' '.$resp->body()
                );
            }
    
            $json = $resp->json();
            // Prefer exact TTL from expires_at if provided (epoch seconds)
            if (!empty($json['expires_at'])) {
                $ttl = max(60, $json['expires_at'] - time() - 60); // refresh 1 min early
                Cache::put('phonepe_access_token', $json['access_token'], now()->addSeconds($ttl));
            }
    
            return $json['access_token'] ?? throw new \RuntimeException('No access_token in response');
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
        $urls = config('phonepe.base_urls');
        // Endpoint per docs: POST /checkout/v2/sdk/order
        $url = "{$urls['sandbox']['checkout']}/sdk/order";
       // dd($this->authHeaders());
        $response = Http::withHeaders($this->authHeaders())
            ->post($url, $payload)
            ->throw();

        return $response->json();
    }

    // === Check Order Status ===
    public function getOrderStatus(string $merchantOrderId, bool $details = false, bool $errorContext = false): array
    {
        $urls = config('phonepe.base_urls');
        // Endpoint per docs: GET /checkout/v2/order/{merchantOrderId}/status?details=&errorContext=
        $qs = http_build_query(['details' => $details ? 'true' : 'false', 'errorContext' => $errorContext ? 'true' : 'false']);
        $url = "{$urls['sandbox']['checkout']}/order/{$merchantOrderId}/status?{$qs}";

        $response = Http::withHeaders($this->authHeaders())
            ->get($url)
            ->throw();

        return $response->json();
    }

    // === Initiate Refund ===
    public function initiateRefund(array $payload): array
    {
        $urls = config('phonepe.base_urls');
        // Endpoint per docs: POST /payments/v2/refund
        $url = "{$urls['sandbox']['payments']}/refund";

        $response = Http::withHeaders($this->authHeaders())
            ->post($url, $payload)
            ->throw();

        return $response->json();
    }

    // === Refund Status ===
    public function getRefundStatus(string $merchantRefundId): array
    {
        $urls = config('phonepe.base_urls');
        // Endpoint per docs: GET /payments/v2/refund/{merchantRefundId}/status
        $url = "{$urls['sandbox']}/refund/{$merchantRefundId}/status";

        $response = Http::withHeaders($this->authHeaders())
            ->get($url)
            ->throw();

        return $response->json();
    }
}

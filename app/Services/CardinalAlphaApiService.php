<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CardinalAlphaApiService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.cardinal_alpha.api_url');
        $this->token = config('services.cardinal_alpha.api_token');
    }

    /**
     * Generate a new API token
     */
    public function generateToken(string $email): array
    {
        $response = Http::post("{$this->baseUrl}/api/generate-token", [
            'email' => $email,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to generate token: ' . $response->body());
    }

    /**
     * Get all investors from the API
     */
    public function getInvestors(): array
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/api/investor");

        if ($response->successful()) {
            return $response->json('data', []);
        }

        Log::error('Failed to fetch investors', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        throw new \Exception('Failed to fetch investors: ' . $response->body());
    }

    /**
     * Get a specific investor by ID
     */
    public function getInvestor(int $id): array
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/api/investor/{$id}");

        if ($response->successful()) {
            return $response->json('data', []);
        }

        throw new \Exception('Failed to fetch investor: ' . $response->body());
    }

    /**
     * Create a new investor
     */
    public function createInvestor(array $data): array
    {
        $response = Http::withToken($this->token)
            ->post("{$this->baseUrl}/api/investor", $data);

        if ($response->successful()) {
            return $response->json('data', []);
        }

        throw new \Exception('Failed to create investor: ' . $response->body());
    }

    /**
     * Update an existing investor
     */
    public function updateInvestor(int $id, array $data): array
    {
        $response = Http::withToken($this->token)
            ->put("{$this->baseUrl}/api/investor/{$id}", $data);

        if ($response->successful()) {
            return $response->json('data', []);
        }

        throw new \Exception('Failed to update investor: ' . $response->body());
    }

    /**
     * Get all funds from the API
     */
    public function getFunds(): array
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/api/fund");

        if ($response->successful()) {
            return $response->json('data', []);
        }

        Log::error('Failed to fetch funds', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        throw new \Exception('Failed to fetch funds: ' . $response->body());
    }

    /**
     * Get all investments from the API
     */
    public function getInvestments(): array
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/api/investments");

        if ($response->successful()) {
            return $response->json('data', []);
        }

        Log::error('Failed to fetch investments', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        throw new \Exception('Failed to fetch investments: ' . $response->body());
    }

    /**
     * Search investors
     */
    public function searchInvestors(string $searchType, string $value): array
    {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/api/investor/search", [
                'search_type' => $searchType,
                'value' => $value,
            ]);

        if ($response->successful()) {
            return $response->json('data', []);
        }

        throw new \Exception('Failed to search investors: ' . $response->body());
    }
}


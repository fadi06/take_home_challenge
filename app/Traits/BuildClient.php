<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait BuildClient
{
    // Abstract method to ensure each class defines its own base URL
    abstract protected function getBaseUrl(): string;

    public function buildClient()
    {
        return Http::baseUrl($this->getBaseUrl());
    }

    public function sendRequest(string $endpoint, array $queryParams = [], array $headers = [], string $method = 'GET')
    {
        $client = $this->buildClient()->withHeaders($headers);

        switch (strtoupper($method)) {
            case 'GET':
                return $client->get($endpoint, $queryParams);
            case 'POST':
                return $client->post($endpoint, $queryParams);
            case 'PUT':
                return $client->put($endpoint, $queryParams);
            case 'DELETE':
                return $client->delete($endpoint, $queryParams);
            default:
                throw new \InvalidArgumentException("Unsupported HTTP method: $method");
        }
    }

}

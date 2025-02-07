<?php

namespace Andrefelipe18\AbacatePay;

use Andrefelipe18\AbacatePay\Services\{BillingService, CustomerService};
use GuzzleHttp\Client;

/**
 * Class AbacatePay
 */
class AbacatePay
{
    private Client $client;

    public function __construct(string $apiKey, string $apiUrl = 'https://api.abacatepay.com/v1/')
    {
        $this->client = new Client([
            'base_uri' => $apiUrl,
            'headers'  => [
                'accept'        => 'application/json',
                'authorization' => "Bearer {$apiKey}",
                'content-type'  => 'application/json',
            ],
        ]);
    }

    public function customer(): CustomerService
    {
        return new CustomerService($this->client);
    }

    public function billing(): BillingService
    {
        return new BillingService($this->client);
    }
}

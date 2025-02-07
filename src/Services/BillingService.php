<?php

namespace Andrefelipe18\AbacatePay\Services;

use Andrefelipe18\AbacatePay\Exceptions\InvalidApiTokenException;
use Andrefelipe18\AbacatePay\Models\{Customer, Product};
use Andrefelipe18\AbacatePay\Responses\BillingResponse;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BillingService
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Create a new billing
     *
     * @param string $frequency Frequency of billing
     * @param string[] $methods Payment methods
     * @param Product[] $products Products
     * @param string $returnUrl URL to return
     * @param string $completionUrl URL to completion
     * @param Customer|null $customer Customer data
     *
     * @throws Exception|GuzzleException
     */
    public function create(
        string    $frequency,
        array     $methods,
        array     $products,
        string    $returnUrl,
        string    $completionUrl,
        ?Customer $customer = null
    ): BillingResponse {
        try {
            $data = [
                'frequency' => $frequency,
                'methods'   => $methods,
                'products'  => array_map(fn (Product $product) => [
                    'externalId'  => $product->externalId,
                    'name'        => $product->name,
                    'description' => $product->description,
                    'quantity'    => $product->quantity,
                    'price'       => $product->price,
                ], $products),
                'returnUrl'     => $returnUrl,
                'completionUrl' => $completionUrl,
            ];

            if ($customer) {
                $data['customer'] = [
                    'email'     => $customer->email,
                    'name'      => $customer->name,
                    'cellphone' => $customer->cellphone,
                    'taxId'     => $customer->taxId,
                ];
            }

            $response   = $this->client->post('billing/create', ['body' => json_encode($data)]);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 401) {
                throw new InvalidApiTokenException();
            }

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!is_array($responseData) || !isset($responseData['data'])) {
                throw new Exception('Invalid response data');
            }

            /** @var array<string, string|int> $data */
            $data = $responseData['data'];

            /** @var array<string, string|int> $products */
            $products = $data['products'];

            /** @var bool $devMode */
            $devMode = $data['devMode'] ?? false;

            return new BillingResponse(
                /** @phpstan-ignore-next-line */
                $data['id'],
                /** @phpstan-ignore-next-line */
                $data['url'],
                /** @phpstan-ignore-next-line */
                $data['amount'],
                /** @phpstan-ignore-next-line */
                $data['status'],
                $devMode,
                $responseData['data']['methods'],
                array_map(fn ($product) => new Product(
                    externalId: $product['externalId'] ?? null,
                    name: $product['name'] ?? '',
                    description: $product['description'] ?? '',
                    quantity: $product['quantity'] ?? 0,
                    price: $product['price'] ?? 0
                ), $products)
            );

        } catch (InvalidApiTokenException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Error: {$e->getMessage()}", $e->getCode());
        }
    }

    /**
     * List all billings
     *
     * @return array<BillingResponse>
     *
     * @throws Exception|InvalidApiTokenException|GuzzleException
     */
    public function list(): array
    {
        try {
            $response   = $this->client->get('billing/list');
            $statusCode = $response->getStatusCode();

            /** @var array<string, mixed> $responseData */
            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($statusCode === 401) {
                throw new InvalidApiTokenException();
            }

            if (!isset($responseData['data']) || !is_array($responseData['data'])) {
                $errorMessage = is_string($responseData['error']) ? $responseData['error'] : 'Unknown error';

                throw new Exception("Error: {$errorMessage}", $statusCode);
            }

            $billings = [];

            foreach ($responseData['data'] as $billingData) {
                if (!is_array($billingData)) {
                    continue;
                }

                $products = array_map(function ($product) {
                    return new Product(
                        externalId: $product['externalId'] ?? null,
                        name: $product['name'] ?? '',
                        description: $product['description'] ?? '',
                        quantity: $product['quantity'] ?? 0,
                        price: $product['price'] ?? 0
                    );
                }, $billingData['products'] ?? []);

                $billings[] = new BillingResponse(
                    (string) ($billingData['id'] ?? ''),
                    (string) ($billingData['url'] ?? ''),
                    (int) ($billingData['amount'] ?? 0),
                    (string) ($billingData['status'] ?? ''),
                    (bool) ($billingData['devMode'] ?? false),
                    $billingData['methods'] ?? [],
                    $products
                );
            }

            return $billings;
        } catch (InvalidApiTokenException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Error: {$e->getMessage()}", $e->getCode());
        }
    }
}

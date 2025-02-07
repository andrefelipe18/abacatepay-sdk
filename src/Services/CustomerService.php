<?php

namespace Andrefelipe18\AbacatePay\Services;

use Andrefelipe18\AbacatePay\Exceptions\InvalidApiTokenException;
use Andrefelipe18\AbacatePay\Models\{Customer, CustomerResponseData};
use Andrefelipe18\AbacatePay\Responses\CustomerResponse;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CustomerService
{
    public function __construct(private Client $client)
    {
    }

    /**
     * Create a new customer
     *
     * @param  Customer  $customer  Customer data
     *
     * @throws Exception|GuzzleException
     */
    public function create(Customer $customer): CustomerResponse
    {
        try {
            $data = array_filter([
                'email'     => $customer->email,
                'name'      => $customer->name,
                'cellphone' => $customer->cellphone,
                'taxId'     => $customer->taxId,
            ], fn ($value) => $value !== null);

            $response   = $this->client->post('customer/create', ['body' => json_encode($data)]);
            $statusCode = $response->getStatusCode();

            /** @var array<string, array<string, ?string>|string> $responseData */
            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($statusCode === 401) {
                throw new InvalidApiTokenException();
            }

            if (!isset($responseData['data']['metadata'])) {
                $errorMessage = is_string($responseData['error']) ? $responseData['error'] : 'Unknown error';

                throw new Exception("Error: {$errorMessage}", $statusCode);
            }

            /** @var array<string, string|null> $customerData */
            $customerData = $responseData['data']['metadata'];

            return new CustomerResponse(
                new CustomerResponseData(
                    $responseData['data']['id'] ?? '',
                    new Customer(
                        $customerData['email'] ?? '',
                        $customerData['name'] ?? null,
                        $customerData['cellphone'] ?? null,
                        $customerData['taxId'] ?? null
                    )
                ),
                null
            );
        } catch (InvalidApiTokenException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Error: {$e->getMessage()}", $e->getCode());
        }
    }

    /**
     * List all customers
     *
     * @return array<CustomerResponseData>
     *
     * @throws Exception|InvalidApiTokenException|GuzzleException
     */
    public function list(): array
    {
        try {
            $response   = $this->client->get('customer/list');
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

            $customers = [];

            foreach ($responseData['data'] as $customerData) {
                if (!is_array($customerData) || !isset($customerData['metadata']) || !is_array($customerData['metadata'])) {
                    continue;
                }

                /** @var string $id */
                $id = $customerData['id'];

                /** @var array<string, string|null> $customerData */
                $customerData = $customerData['metadata'];

                $customers[] = new CustomerResponseData(
                    $id,
                    new Customer(
                        $customerData['email'] ?? '',
                        $customerData['name'] ?? null,
                        $customerData['cellphone'] ?? null,
                        $customerData['taxId'] ?? null
                    )
                );
            }

            return $customers;
        } catch (InvalidApiTokenException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Error: {$e->getMessage()}", $e->getCode());
        }
    }
}

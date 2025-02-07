<?php

namespace Andrefelipe18\AbacatePay\Responses;

use Andrefelipe18\AbacatePay\Models\Product;

/**
 * Class BillingResponse
 *
 * @package Andrefelipe18\AbacatePay\Responses
 */
class BillingResponse
{
    /**
     * BillingResponse constructor.
     *
     * @param  string  $id  Billing's ID create by AbacatePay
     * @param  string  $url  Billing's URL
     * @param  int  $amount  Billing's amount
     * @param  string  $status  Billing's status
     * @param  bool  $devMode  Billing's dev mode
     * @param  string[]  $methods  Billing's payment methods
     * @param  Product[]  $products  Billing's products
     */
    public function __construct(
        public string $id,
        public string $url,
        public int $amount,
        public string $status,
        public bool $devMode,
        public array $methods,
        public array $products
    ) {
    }
}

<?php

namespace Andrefelipe18\AbacatePay\Models;

/**
 * Class CustomerResponseData
 *
 * @package Andrefelipe18\AbacatePay\Models
 */
class CustomerResponseData
{
    /**
     * CustomerResponseData constructor.
     *
     * @param  string  $id  Customer's ID create by AbacatePay
     * @param  Customer  $metadata  Customer's metadata
     */
    public function __construct(
        public string $id,
        public Customer $metadata,
    ) {
    }
}

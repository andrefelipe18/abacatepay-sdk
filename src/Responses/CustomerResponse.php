<?php

namespace Andrefelipe18\AbacatePay\Responses;

use Andrefelipe18\AbacatePay\Models\CustomerResponseData;

/**
 * Class CustomerResponse
 *
 * @package Andrefelipe18\AbacatePay\Responses
 */
class CustomerResponse
{
    /**
     * CustomerResponse constructor.
     *
     * @param CustomerResponseData $data Customer create data
     * @param string|null $error Error message
     */
    public function __construct(
        public CustomerResponseData $data,
        public ?string              $error = null,
    ) {
    }
}

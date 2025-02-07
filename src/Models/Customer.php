<?php

namespace Andrefelipe18\AbacatePay\Models;

/**
 * Class Customer
 */
class Customer
{
    /**
     * Customer constructor.
     *
     * @param  string  $email  Customer's email (required)
     * @param  string|null  $name  Customer's name (optional)
     * @param  string|null  $cellphone  Customer's phone number (optional)
     * @param  string|null  $taxId  Customer's tax ID, can be CPF or CNPJ (optional)
     */
    public function __construct(
        public string $email,
        public ?string $name,
        public ?string $cellphone,
        public ?string $taxId,
    ) {
    }
}

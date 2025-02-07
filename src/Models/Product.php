<?php

namespace Andrefelipe18\AbacatePay\Models;

/**
 * Class Product
 */
class Product
{
    /**
     * Product constructor.
     *
     * @param  ?string  $externalId  The product's id in your system. We use this id to create your product in AbacatePay automatically, so make sure your id is unique.
     * @param  ?string  $name  The product's name (required)
     * @param  ?string  $description  Detailed description of the product (optional)
     * @param  ?int  $quantity  The quantity of the product being purchased (required, ≥ 1)
     * @param  ?int  $price  The price per unit of the product in cents (required, ≥ 100)
     */
    public function __construct(
        public ?string $externalId,
        public ?string $name,
        public ?string $description,
        public ?int $quantity,
        public ?int $price
    ) {
    }
}

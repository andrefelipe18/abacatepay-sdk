# AbacatePay

AbacatePay is a PHP library for integrating with the AbacatePay payment gateway.

## Installation

Install the library using Composer:

```bash
composer require andrefelipe18/abacatepay-php
```

# Usage

## Initialize the Library

First, you need to initialize the AbacatePay class with your API token:

```php
require __DIR__ . '/../vendor/autoload.php';

use Andrefelipe18\AbacatePay\AbacatePay;

$abacatePay = new AbacatePay('your_api_token');
```

## Create a Customer

You can create a new customer using the `create` method:

```php
$customer = $abacatePay->customer()->create(
    new \Andrefelipe18\AbacatePay\Models\Customer(
        'customer@example.com',
        'Customer Name',
        '+1234567890',
        '123456789'
    )
);
```

## List Customers

You can list all customers using the `list` method:

```php
$customers = $abacatePay->customer()->list();
```

## Create a Billing

To create a new billing, use the `create` method:

```php
$billing = $abacatePay->billing()->create(
    'ONE_TIME',
    ['PIX'],
    [
        new \Andrefelipe18\AbacatePay\Models\Product(
            'product_1',
            'Product 1',
            'Product 1 description',
            1,
            1000
        ),
        new \Andrefelipe18\AbacatePay\Models\Product(
            'product_2',
            'Product 2',
            'Product 2 description',
            1,
            2000
        ),
    ],
    'https://example.com/return',
    'https://example.com/completion',
    $customer->data->metadata,
);
```

## List Billings

You can list all billings using the `list` method:

```php
$billings = $abacatePay->billing()->list();
```

# License

Licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contributing

Contributions are welcome! Please open an issue or submit a pull request.
<?php

namespace Andrefelipe18\AbacatePay\Exceptions;

use Exception;

/**
 * Class InvalidApiTokenException
 *
 * @package Andrefelipe18\AbacatePay\Exceptions
 */
class InvalidApiTokenException extends Exception
{
    /**
     * InvalidApiTokenException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(string $message = 'Invalid API token', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

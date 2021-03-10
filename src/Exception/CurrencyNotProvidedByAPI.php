<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CurrencyNotProvidedByAPI extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'The target currency is not provided by API',
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}

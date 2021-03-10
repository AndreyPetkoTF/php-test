<?php

declare(strict_types=1);

namespace App\Service;

use App\GetCurrencyRate;

interface RateServiceInterface
{
    public function getRates(GetCurrencyRate $dto): float;
}

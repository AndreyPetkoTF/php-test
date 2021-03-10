<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\CurrencyNotProvidedByAPI;
use App\GetCurrencyRate;
use App\Model\Currency;

class RateService implements RateServiceInterface
{
    public const RATE_URL = 'https://www.cbr-xml-daily.ru/daily_json.js'; // also can be moved to configs

    private RequestService $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function getRates(GetCurrencyRate $dto): float
    {
        if (0 === $dto->getAmount() || $dto->getInitial()->equals($dto->getTarget())) {
            return $dto->getAmount();
        }

        if (!$dto->getInitial()->equals(Currency::RUB()) && !$dto->getTarget()->equals(Currency::RUB())) {
            throw new \Exception(
                'you can convert only from or to RUB, if you want extend this API please donate :)'
            );
        }

        $content = $this->requestService->getRequestContent(self::RATE_URL);

        if ($dto->getInitial()->equals(Currency::RUB())) {
            $target = $dto->getTarget()->getValue();

            $value = $content->Valute->$target->Value ?? throw new CurrencyNotProvidedByAPI();

            return round($dto->getAmount() / $value, 2);
        }

        $initial = $dto->getInitial()->getValue();

        $value = $content->Valute->$initial->Value ?? throw new CurrencyNotProvidedByAPI();

        return round($dto->getAmount() * $value, 2);
    }
}

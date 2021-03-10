<?php

declare(strict_types=1);

namespace App;

use App\Model\Currency;

class GetCurrencyRate
{
    private float $amount;
    private Currency $initial;
    private Currency $target;

    public function __construct(float $amount, Currency $initial, Currency $target)
    {
        $this->amount = $amount;
        $this->initial = $initial;
        $this->target = $target;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getInitial(): Currency
    {
        return $this->initial;
    }

    public function getTarget(): Currency
    {
        return $this->target;
    }

    public function __toString(): string
    {
        return \sprintf('%s-%s-%s', $this->amount, $this->initial->getValue(), $this->target->getValue());
    }
}

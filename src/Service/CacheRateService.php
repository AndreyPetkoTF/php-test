<?php

declare(strict_types=1);

namespace App\Service;

use App\GetCurrencyRate;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CacheRateService implements RateServiceInterface
{
    private const CACHE_LIFETIME_IN_MINUTES = 5;

    private RateService $rateService;
    private CacheInterface $cache;

    public function __construct(RateService $rateService, CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->rateService = $rateService;
    }

    // for cache calculated value
    // cache adapter should be changed to redis - to work faster and don't use filesystem
    public function getRates(GetCurrencyRate $dto): float
    {
        return $this->cache->get((string) $dto, function (ItemInterface $item) use ($dto) {
            $item->expiresAfter(new \DateInterval(\sprintf('PT%sM', self::CACHE_LIFETIME_IN_MINUTES)));
            return $this->rateService->getRates($dto);
        });
    }
}

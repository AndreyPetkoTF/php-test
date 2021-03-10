<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestService
{
    private const REQUEST_CACHE_LIFETIME_IN_MINUTES = 5;

    private HttpClientInterface $client;
    private CacheInterface $cache;

    public function __construct(HttpClientInterface $client, CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->client = $client;
    }

    public function getRequestContent(string $url): mixed
    {
        return $this->cache->get(\md5($url), function (ItemInterface $item) use ($url) {
            $item->expiresAfter(
                new \DateInterval(\sprintf('PT%sM', self::REQUEST_CACHE_LIFETIME_IN_MINUTES))
            );

            $response = $this->client->request(Request::METHOD_GET, $url);
            $content = $response->getContent();

            return \json_decode($content);
        });
    }
}

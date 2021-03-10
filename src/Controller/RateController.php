<?php

declare(strict_types=1);

namespace App\Controller;

use App\GetCurrencyRate;
use App\Model\Currency;
use App\Service\RateServiceInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RateController
{
    private RateServiceInterface $rateService;

    public function __construct(RateServiceInterface $rateService)
    {
        $this->rateService = $rateService;
    }

    public function rate(Request $request): JsonResponse
    {
        // should be moved to general exception handler
        try {
            $query = $request->query;
            if (!$this->checkForRequiredFields(['amount', 'initial', 'target'], $query)) {
                throw new \InvalidArgumentException(
                    'please provide all required fields: amount, initial, target'
                );
            }

            $dto = new GetCurrencyRate(
                // better use some library for money calculation which will calculate in cents
                (float) $query->get('amount'),
                new Currency($query->get('initial')),
                new Currency($query->get('target'))
            );

            $rate = $this->rateService->getRates($dto);
        } catch (\Exception $exception) {
            return new JsonResponse(
                ['message' => $exception->getMessage()],
                $exception->getCode() !== 0 ? $exception->getCode() : Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(['rate' => $rate], Response::HTTP_OK);
    }

    private function checkForRequiredFields($keys, InputBag $query): bool
    {
        return !array_diff_key(array_flip($keys), $query->all());
    }
}

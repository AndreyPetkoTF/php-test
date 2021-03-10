<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\RateService;
use App\Service\RequestService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// You can run this command by cron */5 * * * *" is you want to create cache manually
class UpdateRatesCommand extends Command
{
    protected static $defaultName = 'update-rates';

    private RequestService $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->requestService->getRequestContent(RateService::RATE_URL);

        return Command::SUCCESS;
    }
}

<?php

namespace Rezzza\CommandBus\Handler;

use Psr\Log\LoggerInterface;
use Rezzza\CommandBus\Bus\DirectBusInterface;
use Rezzza\CommandBus\Command\RetryCommand;
use Rezzza\CommandBus\Consumer\FailStrategy\FailStrategyInterface;
use Rezzza\CommandBus\Exception\CommandHandlerFailedException;

class RetryHandler
{
    private $commandBus;
    private $failStrategy;
    private $logger;

    public function __construct(DirectBusInterface $commandBus, FailStrategyInterface $failStrategy, LoggerInterface $logger = null)
    {
        $this->commandBus   = $commandBus;
        $this->failStrategy = $failStrategy;
        $this->logger       = $logger;
    }

    public function retry(RetryCommand $command)
    {
        if ($this->logger) {
            $this->logger->info(sprintf('[RETRY] command [%s] , tentative number %d', get_class($command->getCommand()), $command->getTryCount()));
        }

        try {
            $this->commandBus->handle($command->getCommand());
        } catch (\Exception $e) {
            $this->failStrategy->onFail(new CommandBus\Exception\CommandHandlerFailedException($command, $e));
        }
    }
}

<?php

namespace Rezzza\CommandBus\Domain\Consumer\FailStrategy;

use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerFailedException;
use Psr\Log\LoggerInterface;

class RequeueStrategy implements FailStrategyInterface
{
    private $commandBus;

    private $logger;

    public function __construct(CommandBusInterface $commandBus, LoggerInterface $logger = null)
    {
        $this->commandBus = $commandBus;
        $this->logger     = $logger;
    }

    public function onFail(CommandHandlerFailedException $exception)
    {
        if ($this->logger) {
            $this->logger->error(sprintf('[RequeueStrategy] command [%s] failed. Requeue it.', get_class($exception->getCommand())));
        }

        $this->commandBus->handle($exception->getCommand());
    }
}

<?php

namespace Rezzza\CommandBus\Domain\Consumer\FailStrategy;

use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerFailedException;
use Psr\Log\LoggerInterface;

class RequeueStrategy implements FailStrategyInterface
{
    private $commandBus;

    private $priority;

    private $logger;

    public function __construct(CommandBusInterface $commandBus, $priority = CommandBusInterface::PRIORITY_LOW, LoggerInterface $logger = null)
    {
        $this->commandBus = $commandBus;
        $this->priority   = $priority;
        $this->logger     = $logger;
    }

    public function onFail(CommandHandlerFailedException $exception)
    {
        if ($this->logger) {
            $this->logger->error(sprintf('[RequeueStrategy] command [%s] failed. Requeue it.', get_class($exception->getCommand())));
        }

        $this->commandBus->handle($exception->getCommand(), $this->priority);
    }
}

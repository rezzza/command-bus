<?php

namespace Rezzza\CommandBus\Consumer\FailStrategy;

use Rezzza\CommandBus\CommandBusInterface;
use Rezzza\CommandBus\Command\FailedCommand;
use Rezzza\CommandBus\Command\RetryCommand;
use Rezzza\CommandBus\Exception\CommandHandlerFailedException;
use Psr\Log\LoggerInterface;

class RetryThenFailStrategy implements FailStrategyInterface
{
    private $commandBus;

    private $failOnCount;

    private $logger;

    public function __construct(CommandBusInterface $commandBus, $failOnCount, LoggerInterface $logger = null)
    {
        $this->commandBus  = $commandBus;
        $this->failOnCount = $failOnCount;
        $this->logger      = $logger;
    }

    public function onFail(CommandHandlerFailedException $exception)
    {
        $command = $exception->getCommand();

        if ($this->logger) {
            $this->logger->error(sprintf('[RetryThenFailStrategy] command [%s] failed, attemps %d.', get_class($command->getCommand()), $command->getTryCount()));
        }

        if ($command instanceof RetryCommand) {
            if ($command->getTryCount() === $this->failOnCount) {
                $command = new FailedCommand($command->getCommand(), $this->failOnCount);
            } else {
                $command->incrementTryCount();
            }
        } elseif ($command instanceof FailedCommand) {
            if ($this->logger) {
                $this->logger->error(sprintf('[RetryThenFailStrategy] command [%s] go to failed queue.', get_class($command->getCommand())));
            }

            return;
        } else {
            $command = new RetryCommand($command);
        }

        $this->commandBus->handle($command);
    }
}

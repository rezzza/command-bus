<?php

namespace Rezzza\CommandBus\Domain\Consumer\FailStrategy;

use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\Command\FailedCommand;
use Rezzza\CommandBus\Domain\Command\RetryCommand;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerFailedException;
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

        if ($this->logger) {
            $this->logger->error(sprintf('[RetryThenFailStrategy] command [%s] failed, attemps %d.', get_class($command->getCommand()), $command->getTryCount()));
        }

        $this->commandBus->handle($command);
    }
}

<?php

namespace Rezzza\CommandBus\Domain\Handler;

use Psr\Log\LoggerInterface;
use Rezzza\CommandBus\Domain\DirectCommandBusInterface;
use Rezzza\CommandBus\Domain\Command\RetryCommand;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerFailedException;

class RetryHandler
{
    private $commandBus;
    private $logger;

    public function __construct(DirectCommandBusInterface $commandBus, LoggerInterface $logger = null)
    {
        $this->commandBus   = $commandBus;
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
            throw new CommandHandlerFailedException($command, $e);
        }
    }
}

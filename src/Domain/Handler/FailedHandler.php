<?php

namespace Rezzza\CommandBus\Domain\Handler;

use Psr\Log\LoggerInterface;
use Rezzza\CommandBus\Domain\Command\FailedCommand;
use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerFailedException;

class FailedHandler
{
    private $commandBus;
    private $logger;

    public function __construct(CommandBusInterface $commandBus, LoggerInterface $logger = null)
    {
        $this->commandBus   = $commandBus;
        $this->logger       = $logger;
    }

    public function failed(FailedCommand $command)
    {
        if ($this->logger) {
            $this->logger->info(sprintf('[FailedCommand] command [%s]', get_class($command->getCommand())));
        }

        try {
            $this->commandBus->handle($command->getCommand());
        } catch (\Exception $e) {
            throw new CommandHandlerFailedException($command, $e);
        }
    }
}

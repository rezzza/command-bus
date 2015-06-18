<?php

namespace Rezzza\CommandBus\Domain\Consumer;

use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\Consumer\FailStrategy\FailStrategyInterface;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerFailedException;

class ConsumerHandler
{
    public function __construct(CommandBusInterface $commandBus, FailStrategyInterface $failStrategy)
    {
        $this->commandBus   = $commandBus;
        $this->failStrategy = $failStrategy;
    }

    public function handle(CommandInterface $command)
    {
        try {
            $this->commandBus->handle($command);
        } catch (\Exception $e) {
            $this->failStrategy->onFail(new CommandHandlerFailedException($command, $e));
        }
    }
}

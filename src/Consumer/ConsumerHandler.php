<?php

namespace Rezzza\CommandBus\Consumer;

use Rezzza\CommandBus\CommandBusInterface;
use Rezzza\CommandBus\Consumer\FailStrategy\FailStrategyInterface;
use Rezzza\CommandBus\Exception\CommandHandlerFailedException;

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

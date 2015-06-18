<?php

namespace Rezzza\CommandBus\Domain\Consumer;

use Rezzza\CommandBus\Domain\Consumer\FailStrategy\FailStrategyInterface;
use Rezzza\CommandBus\Domain\DirectCommandBusInterface;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerFailedException;

class Consumer
{
    /**
     * @param ProviderInterface         $provider     provider
     * @param DirectCommandBusInterface $commandBus   commandBus
     * @param FailStrategyInterface     $failStrategy failStrategy
     */
    public function __construct(ProviderInterface $provider, DirectCommandBusInterface $commandBus, FailStrategyInterface $failStrategy)
    {
        $this->provider     = $provider;
        $this->commandBus   = $commandBus;
        $this->failStrategy = $failStrategy;
    }

    public function consume($command)
    {
        $command = $this->provider->lpop($command);

        if ($command) {
            try {
                $this->commandBus->handle($command);
            } catch (\Exception $e) {
                $this->failStrategy->onFail(new CommandHandlerFailedException($command, $e));
            }
        }
    }
}

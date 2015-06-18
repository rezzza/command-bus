<?php

namespace Rezzza\CommandBus\Consumer;

use Rezzza\CommandBus\Bus\DirectBusInterface;
use Rezzza\CommandBus\Consumer\FailStrategy\FailStrategyInterface;
use Rezzza\CommandBus\Consumer\Provider\ProviderInterface;
use Rezzza\CommandBus\Exception\CommandHandlerFailedException;

class Consumer
{
    /**
     * @param ProviderInterface     $provider     provider
     * @param DirectBusInterface    $commandBus   commandBus
     * @param FailStrategyInterface $failStrategy failStrategy
     */
    public function __construct(ProviderInterface $provider, DirectBusInterface $commandBus, FailStrategyInterface $failStrategy)
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

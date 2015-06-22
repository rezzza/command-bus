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

    /**
     * @param string $command command
     *
     * @return Response|null
     */
    public function consume($command)
    {
        $command = $this->provider->dequeue($command);

        if ($command) {
            try {
                $this->commandBus->handle($command);

                return new Response($command, Response::SUCCESS);
            } catch (\Exception $e) {
                $this->failStrategy->onFail(new CommandHandlerFailedException($command, $e));

                return new Response($command, Response::FAILED, $e);
            }
        }
    }
}

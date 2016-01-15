<?php

namespace Rezzza\CommandBus\Domain\Consumer;

use Rezzza\CommandBus\Domain\Consumer\FailStrategy\FailStrategyInterface;
use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\Event;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerFailedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Consumer
{
    private $provider;
    private $commandBus;
    private $failStrategy;
    private $eventDispatcher;

    /**
     * @param ProviderInterface         $provider     provider
     * @param CommandBusInterface $commandBus   commandBus
     * @param FailStrategyInterface     $failStrategy failStrategy
     * @param EventDispatcherInterface  $eventDispatcher eventDispatcher
     */
    public function __construct(ProviderInterface $provider, CommandBusInterface $commandBus, FailStrategyInterface $failStrategy, EventDispatcherInterface $eventDispatcher)
    {
        if (CommandBusInterface::SYNC_HANDLE_TYPE !== $commandBus->getHandleType()) {
            throw new \RuntimeException('Consume should be powered by a synchronous handle type');
        }

        $this->provider        = $provider;
        $this->commandBus      = $commandBus;
        $this->failStrategy    = $failStrategy;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string|null $command command
     *
     * @return Response|null
     */
    public function consume($command = null)
    {
        $command = $this->provider->dequeue($command);

        if ($command) {
            try {
                $this->commandBus->handle($command);

                $response = new Response($command, Response::SUCCESS);
            } catch (CommandHandlerFailedException $e) {
                $this->failStrategy->onFail($e);

                $response = new Response($e->getCommand(), Response::FAILED, $e->getPrevious());
            } catch (\Exception $e) {
                $this->failStrategy->onFail(new CommandHandlerFailedException($command, $e));

                $response = new Response($command, Response::FAILED, $e);
            }

            $this->eventDispatcher->dispatch(
                Event\Events::ON_CONSUMER_RESPONSE,
                new Event\OnConsumerResponseEvent($this->commandBus->getHandleType(), $response)
            );

            return $response;
        }
    }
}

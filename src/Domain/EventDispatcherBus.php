<?php

namespace Rezzza\CommandBus\Domain;

use Rezzza\CommandBus\Domain\Consumer\Response;
use Rezzza\CommandBus\Domain\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventDispatcherBus implements CommandBusInterface
{
    private $eventDispatcher;

    private $delegateCommandBus;

    public function __construct(EventDispatcherInterface $eventDispatcher, CommandBusInterface $delegateCommandBus)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->delegateCommandBus = $delegateCommandBus;
    }

    public function getHandleType()
    {
        return $this->delegateCommandBus->getHandleType();
    }

    public function handle(CommandInterface $command, $priority = null)
    {
        try {
            $this->eventDispatcher->dispatch(
                Event\Events::PRE_HANDLE_COMMAND,
                new Event\PreHandleCommandEvent($this->getHandleType(), $command)
            );

            $this->delegateCommandBus->handle($command, $priority);

            $this->eventDispatcher->dispatch(
                Event\Events::ON_DIRECT_RESPONSE,
                new Event\OnDirectResponseEvent($this->getHandleType(), new Response($command, Response::SUCCESS))
            );
        } catch (\Exception $e) {
            $this->eventDispatcher->dispatch(
                Event\Events::ON_DIRECT_RESPONSE,
                new Event\OnDirectResponseEvent($this->getHandleType(), new Response($command, Response::FAILED, $e))
            );

            throw $e;
        }
    }
}

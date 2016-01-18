<?php

namespace Rezzza\CommandBus\Domain;

use Psr\Log\LoggerInterface;

class LoggerBus implements CommandBusInterface
{
    private $logger;

    private $delegateCommandBus;

    public function __construct(LoggerInterface $logger, CommandBusInterface $delegateCommandBus)
    {
        $this->logger = $logger;
        $this->delegateCommandBus = $delegateCommandBus;
    }

    public function getHandleType()
    {
        return $this->delegateCommandBus->getHandleType();
    }

    public function handle(CommandInterface $command, $priority = null)
    {
        try {
            $this->logger->notice(
                'CommandBus handle command',
                [
                    'bus' => get_class($this->delegateCommandBus),
                    'handle_type' => $this->getHandleType(),
                    'command' => serialize($command)
                ]
            );
            $this->delegateCommandBus->handle($command, $priority);
        } catch (\Exception $e) {
            $this->logger->error(
                'CommandBus failed to handle command',
                [
                    'bus' => get_class($this->delegateCommandBus),
                    'handle_type' => $this->getHandleType(),
                    'command' => serialize($command),
                    'exception_message' => $e->getMessage()
                ]
            );

            throw $e;
        }
    }
}

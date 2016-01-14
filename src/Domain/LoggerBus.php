<?php

namespace Rezzza\CommandBus\Domain;

use Psr\Log\LoggerInterface;

class LoggerBus implements DirectCommandBusInterface
{
    private $logger;

    private $delegateCommandBus;

    public function __construct(LoggerInterface $logger, CommandBusInterface $delegateCommandBus)
    {
        $this->logger = $logger;
        $this->delegateCommandBus = $delegateCommandBus;
    }

    public function handle(CommandInterface $command, $priority = null)
    {
        try {
            $this->logger->notice(
                'CommandBus handle command',
                [
                    'bus' => get_class($this->delegateCommandBus),
                    'command' => serialize($command)
                ]
            );
            $this->delegateCommandBus->handle($command, $priority);
        } catch (\Exception $e) {
            $this->logger->error(
                'CommandBus failed to handle command',
                [
                    'bus' => get_class($this->delegateCommandBus),
                    'command' => serialize($command),
                    'exception_message' => $e->getMessage()
                ]
            );

            throw $e;
        }
    }
}

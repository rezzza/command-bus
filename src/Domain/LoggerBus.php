<?php

namespace Rezzza\CommandBus\Domain;

use Psr\Log\LoggerInterface;
use Rezzza\CommandBus\Domain\Serializer\CommandSerializerInterface;

class LoggerBus implements CommandBusInterface
{
    private $logger;

    private $delegateCommandBus;

    private $commandSerializer;

    public function __construct(
        LoggerInterface $logger,
        CommandBusInterface $delegateCommandBus,
        CommandSerializerInterface $commandSerializer
    ) {
        $this->logger = $logger;
        $this->delegateCommandBus = $delegateCommandBus;
        $this->commandSerializer = $commandSerializer;
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
                    'command' => $this->commandSerializer->serialize($command)
                ]
            );
            $this->delegateCommandBus->handle($command, $priority);
        } catch (\Exception $e) {
            $this->logger->error(
                'CommandBus failed to handle command',
                [
                    'bus' => get_class($this->delegateCommandBus),
                    'handle_type' => $this->getHandleType(),
                    'command' => $this->commandSerializer->serialize($command),
                    'exception_message' => $e->getMessage()
                ]
            );

            throw $e;
        }
    }
}

<?php

namespace Rezzza\CommandBus\Domain;

use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LoggerBus implements CommandBusInterface
{
    private $logger;

    private $delegateCommandBus;

    private $commandSerializer;

    public function __construct(
        LoggerInterface $logger,
        CommandBusInterface $delegateCommandBus,
        NormalizerInterface $normalizer
    ) {
        $this->logger = $logger;
        $this->delegateCommandBus = $delegateCommandBus;
        $this->normalizer = $normalizer;
    }

    public function getHandleType()
    {
        return $this->delegateCommandBus->getHandleType();
    }

    public function handle(CommandInterface $command, $priority = null)
    {
        try {
            $this->logger->notice(
                sprintf('CommandBus handle command %s', get_class($command)),
                [
                    'bus' => get_class($this->delegateCommandBus),
                    'handle_type' => $this->getHandleType(),
                    'command' => $this->normalizer->normalize($command),
                ]
            );
            return $this->delegateCommandBus->handle($command, $priority);
        } catch (\Exception $e) {
            $this->logger->error(
                sprintf('CommandBus failed to handle command %s', get_class($command)),
                [
                    'bus' => get_class($this->delegateCommandBus),
                    'handle_type' => $this->getHandleType(),
                    'command' => $this->normalizer->normalize($command),
                    'exception_message' => $e->getMessage()
                ]
            );

            throw $e;
        }
    }
}

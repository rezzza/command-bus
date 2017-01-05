<?php

namespace Rezzza\CommandBus\Domain;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LoggerBus implements CommandBusInterface
{
    private $logger;

    private $delegateCommandBus;

    private $commandSerializer;

    private $handleLogLevel;

    private $errorLogLevel;

    public function __construct(
        LoggerInterface $logger,
        CommandBusInterface $delegateCommandBus,
        NormalizerInterface $normalizer,
        $handleLogLevel = LogLevel::NOTICE,
        $errorLogLevel = LogLevel::ERROR
    ) {
        $this->logger = $logger;
        $this->delegateCommandBus = $delegateCommandBus;
        $this->normalizer = $normalizer;
        $this->handleLogLevel = $handleLogLevel;
        $this->errorLogLevel = $errorLogLevel;
    }

    public function getHandleType()
    {
        return $this->delegateCommandBus->getHandleType();
    }

    public function handle(CommandInterface $command, $priority = null)
    {
        try {
            $this->logger->log(
                $this->handleLogLevel,
                sprintf('CommandBus handle command %s', get_class($command)),
                [
                    'bus' => get_class($this->delegateCommandBus),
                    'handle_type' => $this->getHandleType(),
                    'command' => $this->normalizer->normalize($command),
                ]
            );
            return $this->delegateCommandBus->handle($command, $priority);
        } catch (\Exception $e) {
            $this->logger->log(
                $this->errorLogLevel,
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

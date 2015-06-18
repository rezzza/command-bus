<?php

namespace Rezzza\CommandBus\Infra\Provider\Direct;

use Psr\Log\LoggerInterface;
use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Domain\Handler\CommandHandlerLocatorInterface;
use Rezzza\CommandBus\Domain\DirectCommandBusInterface;

class DirectBus implements DirectCommandBusInterface
{
    private $commandStack = [];
    private $executing = false;
    private $locator;
    private $logger;

    /**
     * @param CommandHandlerLocatorInterface $locator locator
     * @param LoggerInterface                $logger  logger
     */
    public function __construct(CommandHandlerLocatorInterface $locator, LoggerInterface $logger = null)
    {
        $this->locator = $locator;
        $this->logger  = $logger;
    }

    public function handle(CommandInterface $command)
    {
        $this->commandStack[] = $command;

        if ($this->executing) {
            return;
        }

        while ($command = array_shift($this->commandStack)) {
            $this->invokeHandler($command);
        }
    }

    private function invokeHandler(CommandInterface $command)
    {
        try {
            $this->executing = true;

            $handler = $this->locator->getCommandHandler($command);

            if ($this->logger) {
                $this->logger->info(sprintf('[DirectCommandBus] Executing Command [%s] with content: [%s]', get_class($command), serialize($command)));
            }

            if (is_callable($handler)) {
                $handler($command);
            } elseif (is_object($handler)) {
                $method  = $this->getHandlerMethodName($command);
                if (!method_exists($handler, $method)) {
                    throw new \RuntimeException(sprintf("Service %s has no method %s to handle command.", get_class($handler), $method));
                }
                $handler->$method($command);
            } else {
                throw new \LogicException(sprintf('Handler locator return a not object|callable handler, type is %s', gettype($handler)));
            }
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error(sprintf('[DirectCommandBus] Command [%s] with content [%s] failed', get_class($command), serialize($command)));
            }
            $this->executing = false;
            throw $e;
        }

        $this->executing = false;
    }

    /**
     * Method \Acme\Foo\Bar\DoActionCommand return doAction.
     *
     * @param CommandInterface $command command
     *
     * @return string
     */
    private function getHandlerMethodName(CommandInterface $command)
    {
        $parts = explode("\\", get_class($command));

        return str_replace("Command", "", lcfirst(end($parts)));
    }
}

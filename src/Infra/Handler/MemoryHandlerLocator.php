<?php

namespace Rezzza\CommandBus\Infra\Handler;

use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerNotFoundException;
use Rezzza\CommandBus\Domain\Handler\CommandHandlerLocatorInterface;

class MemoryHandlerLocator implements CommandHandlerLocatorInterface
{
    /**
     * @var object[]|callable[]
     */
    private $handlers = [];

    /**
     * @param string          $commandClass commandClass
     * @param object|callable $handler      handler
     */
    public function addHandler($commandClass, $handler)
    {
        if (false === is_object($handler) && false === is_callable($handler)) {
            throw new \InvalidArgumentException(sprintf('Handler for class [%s] must be an object or callable', $commandClass));
        }

        $this->handlers[$commandClass] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandHandler(CommandInterface $command)
    {
        $commandClass = get_class($command);

        if (false === array_key_exists($commandClass, $this->handlers)) {
            throw new CommandHandlerNotFoundException($command);
        }

        return $this->handlers[$commandClass];
    }
}

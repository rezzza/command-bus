<?php

namespace Rezzza\CommandBus\Handler;

use Rezzza\CommandBus\CommandInterface;
use Rezzza\CommandBus\Exception\CommandHandlerNotFoundException;

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

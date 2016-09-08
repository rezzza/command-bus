<?php

namespace Rezzza\CommandBus\Infra\Provider\Direct;

use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\Handler\CommandHandlerLocatorInterface;
use Rezzza\CommandBus\Domain\Handler\HandlerDefinition;
use Rezzza\CommandBus\Domain\Handler\HandlerMethodResolverInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DirectBus implements CommandBusInterface
{
    private $locator;
    private $methodResolver;

    /**
     * @param CommandHandlerLocatorInterface $locator
     * @param HandlerMethodResolverInterface $methodResolver
     */
    public function __construct(CommandHandlerLocatorInterface $locator, HandlerMethodResolverInterface $methodResolver)
    {
        $this->locator        = $locator;
        $this->methodResolver = $methodResolver;
    }

    public function getHandleType()
    {
        return CommandBusInterface::SYNC_HANDLE_TYPE;
    }

    public function handle(CommandInterface $command, $priority = null)
    {
        $handler = $this->locator->getCommandHandler($command);

        if (is_callable($handler)) {
            return $handler($command);
        } elseif (is_object($handler)) {
            $method = null;
            if ($handler instanceof HandlerDefinition) {
                $method  = $handler->getMethod();
                $handler = $handler->getObject();
            }

            if (null === $method) {
                $method  = $this->methodResolver->resolveMethodName($command, $handler);
            }

            if (!method_exists($handler, $method)) {
                throw new \RuntimeException(sprintf("Service %s has no method %s to handle command.", get_class($handler), $method));
            }
            return $handler->$method($command);
        } else {
            throw new \LogicException(sprintf('Handler locator return a not object|callable handler, type is %s', gettype($handler)));
        }
    }
}

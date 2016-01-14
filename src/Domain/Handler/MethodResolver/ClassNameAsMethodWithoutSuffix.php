<?php

namespace Rezzza\CommandBus\Domain\Handler\MethodResolver;

use Rezzza\CommandBus\Domain\Handler\HandlerMethodResolverInterface;
use Rezzza\CommandBus\Domain\CommandInterface;

/**
 * Command \Acme\Foo\Bar\DoActionCommand return doAction.
 */
class ClassNameAsMethodWithoutSuffix implements HandlerMethodResolverInterface
{
    public function resolveMethodName(CommandInterface $command, $commandHandler)
    {
        $parts = explode("\\", get_class($command));

        return str_replace("Command", "", lcfirst(end($parts)));
    }
}

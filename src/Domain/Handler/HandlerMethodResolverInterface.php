<?php

namespace Rezzza\CommandBus\Domain\Handler;

use Rezzza\CommandBus\Domain\CommandInterface;

interface HandlerMethodResolverInterface
{
    public function resolveMethodName(CommandInterface $command, $commandHandler);
}

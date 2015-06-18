<?php

namespace Rezzza\CommandBus\Consumer\FailStrategy;

use Rezzza\CommandBus\Exception\CommandHandlerFailedException;

interface FailStrategyInterface
{
    public function onFail(CommandHandlerFailedException $exception);
}

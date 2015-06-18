<?php

namespace Rezzza\CommandBus\Domain\Consumer\FailStrategy;

use Rezzza\CommandBus\Domain\Exception\CommandHandlerFailedException;

interface FailStrategyInterface
{
    public function onFail(CommandHandlerFailedException $exception);
}

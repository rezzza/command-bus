<?php

namespace Rezzza\CommandBus\Domain\Command;

use Rezzza\CommandBus\Domain\CommandInterface;

class RetryCommand extends FailedCommand implements CommandInterface
{
    public function __construct(CommandInterface $command)
    {
        parent::__construct($command, 1);
    }

    public function incrementTryCount()
    {
        $this->tryCount++;
    }
}

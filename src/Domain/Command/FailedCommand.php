<?php

namespace Rezzza\CommandBus\Domain\Command;

use Rezzza\CommandBus\Domain\CommandInterface;

class FailedCommand implements CommandInterface
{
    protected $tryCount;

    public function __construct(CommandInterface $command, $tryCount = 1)
    {
        $this->command = $command;
        $this->tryCount = $tryCount;
    }

    public function getTryCount()
    {
        return $this->tryCount;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function incrementTryCount()
    {
        $this->tryCount++;
    }
}

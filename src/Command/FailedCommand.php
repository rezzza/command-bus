<?php

namespace Rezzza\CommandBus\Command;

use Rezzza\CommandBus\CommandInterface;

class FailedCommand implements CommandInterface
{
    protected $tryCount;

    public function __construct(CommandInterface $command, $tryCount = 0)
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
}

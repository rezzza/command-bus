<?php

namespace Rezzza\CommandBus\Domain\Event;

use Rezzza\CommandBus\Domain\CommandInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * PreHandleCommandEvent
 *
 * @uses Event
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class PreHandleCommandEvent extends Event
{
    private $handleType;

    private $command;

    public function __construct($handleType, CommandInterface $command)
    {
        $this->handleType = $handleType;
        $this->command = $command;
    }

    public function getHandleType()
    {
        return $this->handleType;
    }

    public function getCommand()
    {
        return $this->command;
    }
}

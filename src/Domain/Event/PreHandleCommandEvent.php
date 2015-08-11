<?php

namespace Rezzza\CommandBus\Domain\Event;

use Rezzza\CommandBus\Domain\CommandBusInterface;
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
    public function __construct(CommandBusInterface $bus, CommandInterface $command)
    {
        $this->bus     = $bus;
        $this->command = $command;
    }

    public function getBus()
    {
        return $this->bus;
    }

    public function getCommand()
    {
        return $this->command;
    }
}

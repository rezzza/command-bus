<?php

namespace Rezzza\CommandBus\Handler;

use Rezzza\CommandBus\CommandInterface;
use Rezzza\CommandBus\Exception\CommandHandlerNotFoundException;

interface CommandHandlerLocatorInterface
{
    /**
     * Return command handler assigned to this command.
     *
     * @param CommandInterface $command command
     * @throws CommandHandlerNotFoundException
     *
     * @return object
     */
    public function getCommandHandler(CommandInterface $command);
}

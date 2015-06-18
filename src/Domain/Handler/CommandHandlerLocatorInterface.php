<?php

namespace Rezzza\CommandBus\Domain\Handler;

use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Domain\Exception\CommandHandlerNotFoundException;

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

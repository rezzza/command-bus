<?php

namespace Rezzza\CommandBus\Domain\Exception;

use Rezzza\CommandBus\Domain\CommandInterface;

class CommandHandlerNotFoundException extends \LogicException
{
    public function __construct(CommandInterface $command)
    {
        $message = sprintf('Command handler not found for command [%s]', get_class($command));

        parent::__construct($message);
    }
}

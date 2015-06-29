<?php

namespace Rezzza\CommandBus\Domain\Command;

use Rezzza\CommandBus\Domain\CommandInterface;

class RetryCommand extends FailedCommand implements CommandInterface
{
}

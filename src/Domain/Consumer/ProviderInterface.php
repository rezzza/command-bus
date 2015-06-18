<?php

namespace Rezzza\CommandBus\Domain\Consumer;

use Rezzza\CommandBus\Domain\CommandInterface;

interface ProviderInterface
{
    /**
     * @param string $command command
     *
     * @return CommandInterface|null
     */
    public function lpop($command);
}

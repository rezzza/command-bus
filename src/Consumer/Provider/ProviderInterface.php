<?php

namespace Rezzza\CommandBus\Consumer\Provider;

use Rezzza\CommandBus\CommandInterface;

interface ProviderInterface
{
    /**
     * @param string $command command
     *
     * @return CommandInterface|null
     */
    public function lpop($command);
}

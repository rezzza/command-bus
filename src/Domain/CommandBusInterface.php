<?php

namespace Rezzza\CommandBus\Domain;

interface CommandBusInterface
{
    CONST PRIORITY_HIGH = 10;
    CONST PRIORITY_LOW  = 0;

    public function handle(CommandInterface $command, $prority = null);
}

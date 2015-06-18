<?php

namespace Rezzza\CommandBus;

interface CommandBusInterface
{
    public function handle(CommandInterface $command);
}

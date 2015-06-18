<?php

namespace Rezzza\CommandBus\Domain;

interface CommandBusInterface
{
    public function handle(CommandInterface $command);
}

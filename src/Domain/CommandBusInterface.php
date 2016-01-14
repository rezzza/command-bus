<?php

namespace Rezzza\CommandBus\Domain;

interface CommandBusInterface
{
    CONST PRIORITY_HIGH = 10;
    CONST PRIORITY_LOW  = 0;

    /** The bus will delegate to another process the handle of the command */
    CONST ASYNC_HANDLE_TYPE = 'async';
    /** The bus will handle itself the command execution */
    CONST SYNC_HANDLE_TYPE = 'sync';

    public function handle(CommandInterface $command, $priority = null);

    /**
     * Should return CommandBusInterface::ASYNC_HANDLE_TYPE or CommandBusInterface::SYNC_HANDLE_TYPE
     *
     * @return string
     */
    public function getHandleType();
}

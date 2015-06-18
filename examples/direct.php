<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/tools/FooCommand.php';

use Rezzza\CommandBus;

$command = new FooCommand(uniqid());

$handlerLocator = new CommandBus\Infra\Handler\MemoryHandlerLocator();
$handlerLocator->addHandler('FooCommand', function($command) {
    echo sprintf('Launch command [%s] with id: %s', get_class($command), $command->getId());
});

$bus = new CommandBus\Infra\Provider\Direct\DirectBus($handlerLocator);
$bus->handle($command);

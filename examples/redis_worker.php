<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/tools/FooCommand.php';
require_once __DIR__.'/tools/Logger.php';

if (false === array_key_exists(1, $argv)) {
    throw new \InvalidArgumentException(sprintf('Please provide a Command to consume.'));
}

$commandToConsume = $argv[1];

use Rezzza\CommandBus;

$logger = new Logger();

// redis bus
$redis = new \Redis();
$redis->connect('127.0.0.1');
$redisBus = new CommandBus\Bus\Redis($redis, $logger);

// direct bus and its handlers.
$handlerLocator = new CommandBus\Handler\MemoryHandlerLocator();
$directBus      = new CommandBus\Bus\Direct($handlerLocator, $logger);

// fail strategy, if command fail, it'll retry 10 times in a retry queue then go to a failed queue..
$failStrategy = new CommandBus\Consumer\FailStrategy\RetryThenFailStrategy($redisBus, 10, $logger);
// With requeue strategy, not useful to use Retry and Failed handlers|consumers.
//$failStrategy = new CommandBus\Consumer\FailStrategy\RequeueStrategy($redisBus, $logger);
//$failStrategy = new CommandBus\Consumer\FailStrategy\NoneStrategy($logger);

$handlerLocator->addHandler('FooCommand', function($command) {
    $rand = rand(1, 10);

    if ($rand != 7) {
        throw new \Exception('coucou');
    }
});

$handlerLocator->addHandler('Rezzza\CommandBus\Command\RetryCommand', new CommandBus\Handler\RetryHandler($directBus, $failStrategy, $logger));
$handlerLocator->addHandler('Rezzza\CommandBus\Command\FailedCommand', function($command) {
    echo chr(10).sprintf('[FAILED] command [%s], number of tenatives %d', get_class($command), $command->getTryCount());
});

// consumer
$consumer = new CommandBus\Consumer\Consumer(
    new CommandBus\Consumer\Provider\Redis($redis),
    $directBus,
    $failStrategy
);

do {
    $consumer->consume($commandToConsume);
    $logger->info('----------------------------------');

    echo '.';
    sleep(1); // yep ...
} while (true);

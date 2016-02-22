<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/tools/FooCommand.php';
require_once __DIR__.'/tools/Logger.php';

if (false === array_key_exists(1, $argv)) {
    throw new \InvalidArgumentException(sprintf('Please provide a Command to consume.'));
}

$commandToConsume = $argv[1];

use Rezzza\CommandBus;

$logger          = new Logger();
$eventDispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();

// redis bus
$redis = new \Redis();
$redis->connect('127.0.0.1');

$redisKeyGenerator = new CommandBus\Infra\Provider\Redis\RedisKeyGenerator();
$serializer        = new CommandBus\Infra\Serializer\NativeSerializer();

$redisBus = new CommandBus\Infra\Provider\Redis\RedisBus($redis, $redisKeyGenerator, $serializer);

// direct bus and its handlers.
$handlerLocator = new CommandBus\Infra\Handler\MemoryHandlerLocator();
$handlerMethodNameResolver = new CommandBus\Domain\Handler\MethodResolver\ClassNameAsMethodWithoutSuffix;
$directBus      = new CommandBus\Infra\Provider\Direct\DirectBus($handlerLocator, $handlerMethodNameResolver);

// fail strategy, if command fail, it'll retry 10 times in a retry queue then go to a failed queue..
$failStrategy = new CommandBus\Domain\Consumer\FailStrategy\RetryThenFailStrategy($redisBus, 10, $logger);
// With requeue strategy, not useful to use Retry and Failed handlers|consumers.
//$failStrategy = new CommandBus\Domain\Consumer\FailStrategy\RequeueStrategy($redisBus, $logger);
//$failStrategy = new CommandBus\Domain\Consumer\FailStrategy\NoneStrategy($logger);

$handlerLocator->addHandler('FooCommand', function ($command) {
    $rand = rand(1, 10);

    if ($rand != 7) {
        throw new \Exception('coucou');
    }
});

$handlerLocator->addHandler('Rezzza\CommandBus\Domain\Command\RetryCommand', new CommandBus\Domain\Handler\RetryHandler($directBus, $logger));
$handlerLocator->addHandler('Rezzza\CommandBus\Domain\Command\FailedCommand', new CommandBus\Domain\Handler\FailedHandler($directBus, $logger));

$loggerBus = new Rezzza\CommandBus\Domain\LoggerBus(
    $logger,
    $directBus,
    new \Symfony\Component\Serializer\Serializer([
        new \Symfony\Component\Serializer\Normalizer\PropertyNormalizer
    ])
);

// consumer
$consumer = new CommandBus\Domain\Consumer\Consumer(
    new CommandBus\Infra\Provider\Redis\RedisConsumerProvider($redis, $redisKeyGenerator, $serializer),
    $loggerBus,
    $failStrategy,
    $eventDispatcher
);

do {
    $consumer->consume($commandToConsume);
    $logger->info('----------------------------------');

    echo '.';
    usleep(1); // yep ...
} while (true);

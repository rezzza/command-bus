Rezzza\CommandBus
-----------------

Light command bus.

# Installation

`composer.json`:

```json
    "rezzza/command-bus": "~1.0@dev"
```

# Command

Commands must implements an interface `Rezzza\CommandBus\Domain\CommandInterface`, it should be value object, example:

```php
class ShortenUrlCommand
{
    private $longUrl;

    public function __construct($longUrl)
    {
        $this->longUrl = $longUrl;
    }

    public function getLongUrl()
    {
        return $this->longUrl;
    }
}
```

# Command Handlers

A command handler will be called by the command bus when it handle command. 

The handler could be:

    - A callable (\Closure or a callback)
    - An object. In example of ShortenUrlCommand, the bus will execute `$object->shortenUrl($command)`

# Command bus

It'll find a command handler then handle the command.
We provide at this moment two command bus:

    - Direct (synchronous)
    - Redis (asynchronous)
    - Implement your own command bus with `\Rezzza\CommandBus\Domain\CommandBusInterface`

You can see `examples` to see them in action.

# Fail Strategies

When the bus handle the command, and the command handler fail, you may want to re-queue the command to be executed later.

    - RetryThenFailStrategy: The command is requeued in a `Retry` queue, you'll be able to consume this queue and configure how many time you want to execute it before it goes to a `Fail` queue. Look at `examples/redis_worker.php` example to understand how it work.
    - RequeueStrategy: The command is requeued
    - NoneStrategy: The command will not being requeued.
    - Your own strategy with `Rezzza\CommandBus\Domain\Consumer\FailStrategy\FailStrategyInterface`

# Consumer

In your console command, you can use a consumer to handle asynchronous commands. For example with redis, you'll do

```php
do {
    $redis    = new \Redis();
    $redis->connect('......');

    $handlerLocator = new Rezzza\CommandBus\Infra\Handler\MemoryHandlerLocator();
    // add some handlers ...
    $directBus      = new Rezzza\CommandBus\Infra\Provider\Direct\DirectBus($handlerLocator);
    $failStrategy = new Rezzza\CommandBus\Domain\Consumer\FailStrategy\NoneStrategy();

    $consumer = new Rezzza\CommandBus\Domain\Consumer\Consumer(
        new Rezzza\CommandBus\Infra\Provider\Redis\RedisConsumerProvider($redis),
        $directBus,
        $failStrategy
    );

    $consumer->consume('FooCommand');
    sleep(1); // yep ...
} while (true);
```

Examples are the best documentation, look at `examples` directory.

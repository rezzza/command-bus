<?php

namespace Rezzza\CommandBus\Infra\Provider\Redis;

use Psr\Log\LoggerInterface;
use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Domain\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RedisBus implements CommandBusInterface
{
    /**
     * @var \Redis
     */
    protected $client;

    /**
     * @var RedisKeyGeneratorInterface
     */
    protected $keyGenerator;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param \Redis                     $client          client
     * @param RedisKeyGeneratorInterface $keyGenerator    keyGenerator
     * @param EventDispatcherInterface   $eventDispatcher eventDispatcher
     * @param LoggerInterface            $logger          logger
     */
    public function __construct(\Redis $client, RedisKeyGeneratorInterface $keyGenerator, EventDispatcherInterface $eventDispatcher, LoggerInterface $logger = null)
    {
        $this->client          = $client;
        $this->keyGenerator    = $keyGenerator;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger          = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CommandInterface $command)
    {
        if ($this->logger) {
            $this->logger->info(sprintf('[RedisCommandBus] Add command [%s] with content [%s] to the queue', get_class($command), serialize($command)));
        }

        $this->eventDispatcher->dispatch(Event\Events::PRE_HANDLE_COMMAND, new Event\PreHandleCommandEvent($this, $command));

        $this->client->rpush(
            $this->keyGenerator->generate(get_class($command)),
            serialize($command)
        );
    }
}

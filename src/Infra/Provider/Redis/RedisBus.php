<?php

namespace Rezzza\CommandBus\Infra\Provider\Redis;

use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Domain\Event;
use Rezzza\CommandBus\Domain\Serializer\CommandSerializerInterface;
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
     * @var CommandSerializerInterface
     */
    protected $serializer;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param \Redis                     $client          client
     * @param RedisKeyGeneratorInterface $keyGenerator    keyGenerator
     * @param CommandSerializerInterface $serializer      serializer
     * @param EventDispatcherInterface   $eventDispatcher eventDispatcher
     */
    public function __construct(\Redis $client, RedisKeyGeneratorInterface $keyGenerator, CommandSerializerInterface $serializer, EventDispatcherInterface $eventDispatcher)
    {
        $this->client            = $client;
        $this->keyGenerator      = $keyGenerator;
        $this->serializer        = $serializer;
        $this->eventDispatcher   = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CommandInterface $command, $priority = null)
    {
        $this->eventDispatcher->dispatch(Event\Events::PRE_HANDLE_COMMAND, new Event\PreHandleCommandEvent($this, $command));

        $serializedCommand = $this->serializer->serialize($command);

        $redisMethod = ($priority >= CommandBusInterface::PRIORITY_HIGH) ? 'lpush' : 'rpush';

        $this->client->{$redisMethod}(
            $this->keyGenerator->generate(get_class($command)),
            $serializedCommand
        );
    }
}

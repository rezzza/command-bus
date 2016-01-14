<?php

namespace Rezzza\CommandBus\Infra\Provider\Redis;

use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Domain\Serializer\CommandSerializerInterface;

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
     * @param \Redis                     $client          client
     * @param RedisKeyGeneratorInterface $keyGenerator    keyGenerator
     * @param CommandSerializerInterface $serializer      serializer
     */
    public function __construct(\Redis $client, RedisKeyGeneratorInterface $keyGenerator, CommandSerializerInterface $serializer)
    {
        $this->client            = $client;
        $this->keyGenerator      = $keyGenerator;
        $this->serializer        = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CommandInterface $command, $priority = null)
    {
        $serializedCommand = $this->serializer->serialize($command);

        $redisMethod = ($priority >= CommandBusInterface::PRIORITY_HIGH) ? 'lpush' : 'rpush';

        $this->client->{$redisMethod}(
            $this->keyGenerator->generate(get_class($command)),
            $serializedCommand
        );
    }
}

<?php

namespace Rezzza\CommandBus\Infra\Provider\Redis;

use Rezzza\CommandBus\Domain\Consumer\ProviderInterface;
use Rezzza\CommandBus\Domain\Serializer\CommandSerializerInterface;

class RedisConsumerProvider implements ProviderInterface
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
     * @var integer (see BLPOP definition)
     */
    protected $readBlockTimeout;

    /**
     * @param \Redis                     $client           client
     * @param RedisKeyGeneratorInterface $keyGenerator     keyGenerator
     * @param int                        $readBlockTimeout readBlockTimeout
     */
    public function __construct(\Redis $client, RedisKeyGeneratorInterface $keyGenerator, CommandSerializerInterface $serializer, $readBlockTimeout = 0)
    {
        $this->client           = $client;
        $this->keyGenerator     = $keyGenerator;
        $this->serializer       = $serializer;
        $this->readBlockTimeout = $readBlockTimeout;
    }

    /**
     * {@inheritdoc}
     */
    public function dequeue($commandClass)
    {
        $commandSerialized = $this->client->blpop(
            $this->keyGenerator->generate($commandClass), (int) $this->readBlockTimeout
        );

        return false === empty($commandSerialized) ? $this->serializer->deserialize($commandSerialized[1]) : null;
    }
}

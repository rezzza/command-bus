<?php

namespace Rezzza\CommandBus\Infra\Provider\Redis;

use Rezzza\CommandBus\Domain\Consumer\ProviderInterface;

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
     * @param \Redis $client client
     */
    public function __construct(\Redis $client, RedisKeyGeneratorInterface $keyGenerator)
    {
        $this->client       = $client;
        $this->keyGenerator = $keyGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function dequeue($commandClass)
    {
        $commandSerialized = $this->client->lpop(
            $this->keyGenerator->generate($commandClass)
        );

        return $commandSerialized ? unserialize($commandSerialized) : null;
    }
}

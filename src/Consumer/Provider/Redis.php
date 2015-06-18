<?php

namespace Rezzza\CommandBus\Consumer\Provider;

use Rezzza\CommandBus\Bus\Redis as RedisBus;

class Redis implements ProviderInterface
{
    /**
     * @var \Redis
     */
    protected $client;

    /**
     * @param \Redis $client client
     */
    public function __construct(\Redis $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function lpop($command)
    {
        $commandSerialized = $this->client->lpop(RedisBus::getRedisKey($command));

        return $commandSerialized ? unserialize($commandSerialized) : null;
    }
}

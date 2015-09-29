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
     * @var integer (see BLPOP definition)
     */
    protected $readBlockTimeout;

    /**
     * @param \Redis                     $client           client
     * @param RedisKeyGeneratorInterface $keyGenerator     keyGenerator
     * @param int                        $readBlockTimeout readBlockTimeout
     */
    public function __construct(\Redis $client, RedisKeyGeneratorInterface $keyGenerator, $readBlockTimeout = 0)
    {
        $this->client           = $client;
        $this->keyGenerator     = $keyGenerator;
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

        return false === empty($commandSerialized) ? unserialize($commandSerialized[1]) : null;
    }
}

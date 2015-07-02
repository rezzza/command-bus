<?php

namespace Rezzza\CommandBus\Infra\Provider\Redis;

use Psr\Log\LoggerInterface;
use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\CommandInterface;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param \Redis                     $client       client
     * @param RedisKeyGeneratorInterface $keyGenerator keyGenerator
     * @param LoggerInterface            $logger       logger
     */
    public function __construct(\Redis $client, RedisKeyGeneratorInterface $keyGenerator, LoggerInterface $logger = null)
    {
        $this->client       = $client;
        $this->keyGenerator = $keyGenerator;
        $this->logger       = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CommandInterface $command)
    {
        if ($this->logger) {
            $this->logger->info(sprintf('[RedisCommandBus] Add command [%s] with content [%s] to the queue', get_class($command), serialize($command)));
        }

        $this->client->rpush(
            $this->keyGenerator->generate(get_class($command)),
            serialize($command)
        );
    }
}

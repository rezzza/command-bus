<?php

namespace Rezzza\CommandBus\Bus;

use Psr\Log\LoggerInterface;
use Rezzza\CommandBus\CommandBusInterface;
use Rezzza\CommandBus\CommandInterface;

class Redis implements CommandBusInterface
{
    CONST PREFIX = 'rezzza_command_bus:';

    /**
     * @var \Redis
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param \Redis          $client client
     * @param LoggerInterface $logger logger
     */
    public function __construct(\Redis $client, LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CommandInterface $command)
    {
        if ($this->logger) {
            $this->logger->info(sprintf('[RedisCommandBus] Add command [%s] with content [%s] to the queue', get_class($command), serialize($command)));
        }

        $this->client->rpush($this->getRedisKey(get_class($command)), serialize($command));
    }

    /**
     * @param string $commandClass commandClass
     *
     * @return string
     *
     */
    public static function getRedisKey($commandClass)
    {
        return self::PREFIX.$commandClass;
    }
}

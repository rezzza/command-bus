<?php

namespace Rezzza\CommandBus\Infra\Provider\Redis;

/**
 * RedisKeyGenerator
 *
 * @uses RedisKeyGeneratorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class RedisKeyGenerator implements RedisKeyGeneratorInterface
{
    CONST PREFIX = 'rezzza_command_bus:';

    /**
     * {@inheritdoc}
     */
    public function generate($commandClass = null)
    {
        return self::PREFIX.$commandClass;
    }
}

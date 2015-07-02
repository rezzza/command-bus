<?php

namespace Rezzza\CommandBus\Infra\Provider\Redis;

/**
 * RedisKeyGeneratorInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface RedisKeyGeneratorInterface
{
    /**
     * @param string $commandClass command class
     *
     * @return string
     */
    public function generate($commandClass);
}

<?php

namespace Rezzza\CommandBus\Domain\Serializer;

use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Domain\Exception\CommandSerializationException;

/**
 * CommandSerializerInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface CommandSerializerInterface
{
    /**
     * @param CommandInterface $command command
     *
     * @return string
     */
    public function serialize(CommandInterface $command);

    /**
     * @param string $command command
     *
     * @throws CommandSerializationException
     * @return CommandInterface
     */
    public function deserialize($command);
}

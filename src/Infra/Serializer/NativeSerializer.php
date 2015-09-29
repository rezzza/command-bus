<?php

namespace Rezzza\CommandBus\Infra\Serializer;

use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Domain\Exception\CommandSerializationException;
use Rezzza\CommandBus\Domain\Serializer\CommandSerializerInterface;

class NativeSerializer implements CommandSerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function serialize(CommandInterface $command)
    {
        return serialize($command);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($command)
    {
        $result = unserialize($command);

        if (false === $result instanceof CommandInterface) {
            throw new CommandSerializationException(sprintf('Impossible to deserialize command [%s]', $command));
        }

        return $result;
    }
}

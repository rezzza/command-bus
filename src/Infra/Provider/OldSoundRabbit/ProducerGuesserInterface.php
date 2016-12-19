<?php

namespace Rezzza\CommandBus\Infra\Provider\OldSoundRabbit;

use Rezzza\CommandBus\Domain\CommandInterface;


/**
 * Interface ProducerGuesserInterface
 */
interface ProducerGuesserInterface
{
    /**
     * @param CommandInterface $command
     *
     * @return ProducerInterface
     */
    public function guess(CommandInterface $command);
}

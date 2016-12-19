<?php

namespace Rezzza\CommandBus\Infra\Provider\OldSoundRabbit;

use Rezzza\CommandBus\Domain\CommandBusInterface;
use Rezzza\CommandBus\Domain\CommandInterface;
use Rezzza\CommandBus\Infra\Provider\OldSoundRabbit\ProducerGuesserInterface;

/**
 * Class RabbitMqBus
 */
class RabbitMqBus implements CommandBusInterface
{
    /**
     * @var ProducerGuesserInterface
     */
    private $producerGuesser;

    /**
     * RabbitMqBus constructor.
     *
     * @param ProducerGuesserInterface $producerGuesser
     */
    public function __construct(ProducerGuesserInterface $producerGuesser)
    {
        $this->producerGuesser = $producerGuesser;
    }

    public function getHandleType()
    {
        return CommandBusInterface::ASYNC_HANDLE_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CommandInterface $command, $priority = null)
    {
        $producer = $this->producerGuesser->guess($command);
        $producer->publish(serialize($command));
    }
}

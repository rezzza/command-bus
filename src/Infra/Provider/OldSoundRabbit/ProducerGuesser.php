<?php

namespace Rezzza\CommandBus\Infra\Provider\OldSoundRabbit;

use Rezzza\CommandBus\Domain\CommandInterface;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Rezzza\CommandBusBundle\Provider\OldSoundRabbit\NoProducerFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

/**
 * Class ProducerGuesser
 */
class ProducerGuesser implements ProducerGuesserInterface
{
    const OLD_SOUND_PRODUCER_PATTERN = 'old_sound_rabbit_mq.%s_producer';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param CommandInterface $command
     *
     * @throws NoProducerFoundException
     *
     * @return ProducerInterface
     */
    public function guess(CommandInterface $command)
    {
        $reflection = new \ReflectionClass(get_class($command));
        $className  = rtrim($reflection->getShortName(), 'Command');
        $producerName = Inflector::tableize($className);

        try {
            return $this->container->get(sprintf(static::OLD_SOUND_PRODUCER_PATTERN, $producerName));
        } catch (ServiceNotFoundException $e) {
            throw new NoProducerFoundException($command);
        }
    }
}

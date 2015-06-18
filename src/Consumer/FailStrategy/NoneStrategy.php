<?php

namespace Rezzza\CommandBus\Consumer\FailStrategy;

use Rezzza\CommandBus\Exception\CommandHandlerFailedException;
use Psr\Log\LoggerInterface;

class NoneStrategy implements FailStrategyInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function onFail(CommandHandlerFailedException $exception)
    {
        if ($this->logger) {
            $this->logger->error(sprintf('[NoneStrategy] command [%s] failed.', get_class($exception->getCommand())));
        }
        // noop
    }
}

<?php

namespace Rezzza\CommandBus\Domain\Event;

use Rezzza\CommandBus\Domain\Consumer\Response;
use Symfony\Component\EventDispatcher\Event;

/**
 * OnConsumerResponseEvent
 *
 * @uses Event
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class OnConsumerResponseEvent extends Event
{
    private $handleType;

    private $response;

    /**
     * @param Response $response response
     */
    public function __construct($handleType, Response $response)
    {
        $this->handleType = $handleType;
        $this->response = $response;
    }

    public function getHandleType()
    {
        return $this->handleType;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}

<?php

namespace Rezzza\CommandBus\Domain\Event;

use Rezzza\CommandBus\Domain\Consumer\Response;
use Symfony\Component\EventDispatcher\Event;

/**
 * OnDirectResponseEvent
 *
 * @uses Event
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class OnDirectResponseEvent extends Event
{
    private $handleType;

    private $response;

    /**
     * @param string $handleType
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

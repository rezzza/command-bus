<?php

namespace Rezzza\CommandBus\Domain\Handler;

/**
 * HandlerDefinition
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class HandlerDefinition
{
    /**
     * @var object
     */
    private $object;

    /**
     * @var string
     */
    private $method;

    /**
     * @param object $object object
     * @param string $method method
     */
    public function __construct($object, $method = null)
    {
        $this->object = $object;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}

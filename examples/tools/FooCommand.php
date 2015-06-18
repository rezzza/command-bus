<?php

use Rezzza\CommandBus\Domain\CommandInterface;

class FooCommand implements CommandInterface
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}

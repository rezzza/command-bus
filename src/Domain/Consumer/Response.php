<?php

namespace Rezzza\CommandBus\Domain\Consumer;

use Rezzza\CommandBus\Domain\CommandInterface;

class Response
{
    const SUCCESS = true;
    const FAILED  = false;

    private $command;

    private $success;

    private $error;

    public function __construct(CommandInterface $command, $success, \Exception $error = null)
    {
        $this->command = $command;
        $this->success = (bool) $success;
        $this->error   = $error;
    }

    public function isSuccess()
    {
        return $this->success;
    }

    public function isError()
    {
        return false === $this->isSuccess;
    }

    public function getError()
    {
        return $this->error;
    }
}

<?php

class Logger extends Psr\Log\AbstractLogger implements Psr\Log\LoggerInterface
{
    public function log($level, $message, array $context = array())
    {
        echo chr(10).sprintf('Log lvl [%s]: %s', $level, $message);
    }
}

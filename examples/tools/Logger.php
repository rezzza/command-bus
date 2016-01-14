<?php

class Logger extends Psr\Log\AbstractLogger implements Psr\Log\LoggerInterface
{
    public function log($level, $message, array $context = array())
    {
        echo chr(10).sprintf('Log lvl [%s]: %s : %s', $level, $message, var_export($context, true)).chr(10);
    }
}

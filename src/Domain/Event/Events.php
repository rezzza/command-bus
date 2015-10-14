<?php

namespace Rezzza\CommandBus\Domain\Event;

final class Events
{
    const ON_CONSUMER_RESPONSE = 'on_consumer_response';
    const ON_DIRECT_RESPONSE = 'on_direct_response';
    const PRE_HANDLE_COMMAND   = 'pre_handle_command';
}
